<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            'http://test.fontcrawler.com/',
            'http://www.supreme.de',
            'http://www.fontsquirrel.com',
            'http://html5boilerplate.com/',
            'http://www.yanone.de/',
            'http://www.aquiesdonde.com.ar/',
        );
    }
}
