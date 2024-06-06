<?php

declare(strict_types=1);

namespace Supseven\ThemeBaseAddonSolr\Command;

use ApacheSolrForTypo3\Solr\ConnectionManager;
use ApacheSolrForTypo3\Solr\Domain\Index\IndexService;
use ApacheSolrForTypo3\Solr\Domain\Index\Queue\QueueInitializationService;
use ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ReindexCommand extends Command
{
    public function __construct(
        private readonly SiteRepository             $siteRepository,
        private readonly QueueInitializationService $initializationService,
        private readonly ConnectionManager          $connectionManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Put all items of a type in the queue and index them');
        $this->addArgument('site', InputArgument::OPTIONAL, 'Root page UID of the site. Uses first available if not given');
        $this->addArgument('types', InputArgument::OPTIONAL, 'Comma separated list of types to index, use "*" for all', '*');
        $this->addOption('limit', 'L', InputOption::VALUE_REQUIRED, 'Number of items to index', 9999);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logger = new ConsoleLogger($output);

        if (!ExtensionManagementUtility::isLoaded('solr')) {
            $logger->error('EXT:solr not installed');

            return self::INVALID;
        }

        if (!Environment::getContext()->isDevelopment()) {
            $logger->error('Only available in development context');

            return self::INVALID;
        }

        $siteId = (int)$input->getArgument('site');

        // Fetch site object
        if ($siteId < 1) {
            $site = $this->siteRepository->getFirstAvailableSite(true);

            if ($site) {
                $logger->info(sprintf('Using site with root page %d', $site->getRootPageId()));
            }
        } else {
            $site = $this->siteRepository->getSiteByPageId($siteId);
        }

        if (!$site) {
            $logger->error('Site not found');

            return self::INVALID;
        }

        // Check and validate types
        $config = $site->getSolrConfiguration();
        $typeNames = trim((string)$input->getArgument('types'));

        if ($typeNames === '*') {
            $types = $config->getEnabledIndexQueueConfigurationNames();
        } else {
            $types = GeneralUtility::trimExplode(',', $typeNames, true);

            if (!$types) {
                $logger->error('Type(s) not specified');

                return self::INVALID;
            }

            $knownTypes = $config->getEnabledIndexQueueConfigurationNames();

            foreach ($types as $type) {
                if (!$config->getIndexQueueConfigurationByName($type)) {
                    $logger->error(sprintf('Index queue configuration %s not found', $type));
                    $logger->error(sprintf('Must be one of %s', implode(',', $knownTypes)));

                    return self::INVALID;
                }

                if (!$config->getIndexQueueConfigurationIsEnabled($type)) {
                    $logger->error(sprintf('Index queue configuration %s is not enabled', $type));

                    return self::INVALID;
                }
            }
        }

        $logger->info(sprintf('Deleting documents of types %s in site %d', implode(',', $types), $site->getRootPageId()));

        // Delete all documents of given types from site index
        $siteHash = $site->getSiteHash();
        $solrServers = $this->connectionManager->getConnectionsBySite($site);
        $typeQuery = array_map(
            fn(string $t): string => ' +type:' . $config->getIndexQueueTypeOrFallbackToConfigurationName($t),
            $types
        );

        foreach ($solrServers as $solrServer) {
            $writeService = $solrServer->getWriteService();
            $writeService->deleteByQuery('siteHash:' . $siteHash . implode(' ', $typeQuery));
            $writeService->commit(false, false);
        }

        $logger->info(sprintf('Initializing queue for type %s in site %d', implode(',', $types), $site->getRootPageId()));

        // Delete queue for site
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_solr_indexqueue_item');
        $qb->delete('tx_solr_indexqueue_item');
        $qb->where($qb->expr()->eq('root', $qb->createNamedParameter($site->getRootPageId())));
        $qb->executeStatement();

        // Initialize new queue for site and types
        $this->initializationService->initializeBySiteAndIndexConfigurations($site, $types);

        // Start indexing
        $limit = (int)$input->getOption('limit');
        $logger->info(sprintf('Indexing up to %d items in site %d', $limit, $site->getRootPageId()));

        $indexService = GeneralUtility::makeInstance(IndexService::class, $site);
        $indexService->indexItems($limit);

        return self::SUCCESS;
    }
}
