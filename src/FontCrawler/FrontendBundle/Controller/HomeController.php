<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\FrontendBundle\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Buzz\Browser;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * HomeController.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class HomeController extends Controller
{
    /**
     * The index action.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        $this->get('font_crawler.crawler.repository.host');

        $host     = 'http://www.gameop.de.localhost';
        $browser  = new Browser();
        $response = $browser->get($host);

        $factory  = $this->get('font_crawler.crawler.file_factory');
        $files    = $factory->createFromContent($response, $host);

        die('index');
    }
}
