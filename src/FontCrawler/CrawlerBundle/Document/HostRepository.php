<?php

namespace FontCrawler\CrawlerBundle\Document;

/**
 * HostRepository.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class HostRepository
{
    /**
     * Find hosts.
     *
     * @return array
     */
    public function findHosts()
    {
        return array(
            //'http://www.supreme.de',
            'http://www.fontsquirrel.com',
        );
    }
}
