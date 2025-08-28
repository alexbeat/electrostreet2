<?php

namespace Alexbeat\Electro\Classes;

/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
 */

/**
 * Pagination class
 */
class Pagination
{
    public $total = 0;
    public $page = 1;
    public $limit = 20;
    public $num_links = 6;
    public $url = '';
    public $text_first = '|&lt;';
    public $text_last = '&gt;|';
    public $text_next = '&gt;';
    public $text_prev = '&lt;';
    public $shown = 1;
    public $model = 'category';
    public $model_title = 'товаров';
    /**
     * 
     *
     * @return	text
     */

    public function render()
    {
        $this->url = str_replace('/&', '/?', $this->url);

        $total = $this->total;

        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }

        if (!(int)$this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }

        $num_pages = ceil($total / $limit);

        if ($this->model == 'category') {
            $first_page_limit = $page == 1 ? $limit : $limit - 1; // Количество элементов на первой странице
            $other_pages_limit = $page == 1 ? $limit + 1 : $limit; // Количество элементов на остальных страницах

            if ($total <= $first_page_limit) {
                $num_pages = 1;
            } else {
                $remaining_items = $total - $first_page_limit;
                $num_pages = 1 + ceil($remaining_items / $other_pages_limit);
            }
        }
        // echo $num_pages;



        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $output = '';

        // if ($this->shown >= $this->limit) {
        if ($this->page < $num_pages) {
            $output .= '<a data-add class="btn _mini _fill" href="' . str_replace('{page}', $page + 1, $this->url) . '">Показать еще ...</a>';
        }

        // Начало блока пагинации
        $output .= '<div class="pagination-alt">';

        // Кнопки начала и предыдущей страницы
        if ($page > 1) {
            // $output .= '<a href="' . str_replace('{page}', 1, $this->url) . '"><i class="icon icon-double-arrow _reverse"></i></a>';
            if ($page - 1 != 1) {
                $output .= '<a href="' . str_replace('{page}', $page - 1, $this->url) . '"><i class="icon icon-arrow _reverse"></i></a>';
            }
        }

        // Логика для отображения страниц
        $N = 3;  // Количество страниц вокруг текущей

        if ($num_pages > 1) {
            $start = max(1, $page - floor($N / 2));
            $end = min($num_pages, $page + floor($N / 2));

            if ($start > 1) {
                $output .= '<a href="' . str_replace(['&page={page}', '?page={page}'], '', html_entity_decode($this->url)) . '">1</a>'; // первая
                if ($start > 2) {
                    $output .= '<a class="_simple">...</a>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= '<a class="_active">' . $i . '</a>';
                } else {
                    if ($i != 1) {
                        $output .= '<a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a>';
                    } else {
                        $output .= '<a href="' . str_replace(['&page={page}', '?page={page}'], '', html_entity_decode($this->url)) . '">' . $i . '</a>';
                    }
                }
            }

            if ($end < $num_pages) {
                if ($end < $num_pages - 1) {
                    $output .= '<a class="_simple">...</a>';
                }
                $output .= '<a href="' . str_replace('{page}', $num_pages, $this->url) . '">' . $num_pages . '</a>';
            }
        }

        // Кнопки следующей и последней страницы
        if ($page < $num_pages) {
            $output .= '<a href="' . str_replace('{page}', $page + 1, $this->url) . '"><i class="icon icon-arrow"></i></a>';
            // $output .= '<a href="' . str_replace('{page}', $num_pages, $this->url) . '"><i class="icon icon-double-arrow"></i></a>';
        }

        // Закрываем блок пагинации
        $output .= '</div>';

        // Маленькая аннотация
        if ($total) $output .= '<small>Показано ' . $this->shown . ' из ' . $total . ' ' . $this->model_title . '</small>';

        return $output;
    }


    private function oldRender()
    {
        $total = $this->total;

        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }

        if (!(int)$this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }

        $num_links = $this->num_links;
        $num_pages = ceil($total / $limit);

        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $output = '<div class="pagination">';

        if ($page > 1) {

            if ($page - 1 === 1) {
                $output .= '<a class="pagination__item _prev" href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '"><i class="icon icon-double-arrow"></i><span>Предыдущая</span></a>';
            } else {
                $output .= '<a class="pagination__item _prev" href="' . str_replace('{page}', $page - 1, $this->url) . '"><i class="icon icon-double-arrow"></i><span>Предыдущая</span></a>';
            }
        }

        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);
                $end = $page + floor($num_links / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }

                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= '<a class="pagination__item _active">' . $i . '</a>';
                } else {
                    if ($i === 1) {
                        $output .= '<a class="pagination__item _desktop" href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '">' . $i . '</a>';
                    } else {
                        $output .= '<a class="pagination__item _desktop" href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a>';
                    }
                }
            }
        }

        if ($page < $num_pages) {
            $output .= '<a class="pagination__item" href="' . str_replace('{page}', $page + 1, $this->url) . '"><span>Следующая</span><i class="icon icon-arrow"></i></a>';
        }

        $output .= '</div>';

        if ($num_pages > 1) {
            return $output;
        } else {
            return '';
        }
    }
}
