<?php

/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FontCrawler\CrawlerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * SearchController.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class SearchController extends Controller
{
    /**
     * The index action.
     *
     * @Route("/")
     */
    public function searchAction()
    {
        return $this->render('FontCrawlerCrawlerBundle:Search:form.html.twig');
    }

    /**
     * The result action.
     *
     * @Route("/fonts")
     */
    public function listAction()
    {
        $fonts = $this->get('font_crawler.crawler.manager.font')->findFonts();

        return $this->render('FontCrawlerCrawlerBundle:Search:list.html.twig', array(
            'fonts' => $fonts,
        ));
    }
}
