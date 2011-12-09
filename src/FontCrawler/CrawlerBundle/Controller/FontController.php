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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * FontController.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FontController extends Controller
{
    /**
     * The search term action.
     *
     * @Route(
     *   "/search/{term}",
     *   name="font_search_term",
     *   options={"expose"=true}
     * )
     */
    public function searchTermAction($term)
    {
        $fonts = $this->get('foq_elastica.finder.doc.font')->find($term, 10);

        return $this->render('FontCrawlerCrawlerBundle:Font:list.html.twig', array(
            'fonts' => $fonts,
        ));
    }

    /**
     * The result action.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        $fonts = $this->get('font_crawler.crawler.manager.font')->findFonts();

        return $this->render('FontCrawlerCrawlerBundle:Font:index.html.twig', array(
            'fonts' => $fonts,
        ));
    }
}
