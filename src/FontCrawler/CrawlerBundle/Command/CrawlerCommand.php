<?php

namespace FontCrawler\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CrawlerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fontcrawler:crawl')
            ->setDescription('Crawles the web for fontfaces')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start crawling.');
        $hosts   = $this->getContainer()->get('font_crawler.crawler.repository.host')->findHosts();
        $browser = $this->getContainer()->get('buzz.browser');
        $factory = $this->getContainer()->get('font_crawler.crawler.font_factory');

        foreach ($hosts as $host) {
            $output->writeln(sprintf('Crawling in "%s".', $host));
            $response = $browser->get($host);
            if (200 === $response->getStatusCode()) {
                $fontFaces = $factory->createFromHtml($response->getContent(), $host);
                print_r($fontFaces);
            } else {
                $output->writeln(sprintf('Could not connect to "%s".', $host));
            }
        }

        $output->writeln('Done');
    }
}