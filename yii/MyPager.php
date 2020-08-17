<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/8/6
 * Time: 10:46
 */

namespace xing\helper\yii;


use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class MyPager extends LinkPager
{
    public $goPageLabel = false;

    public $totalPageLable;

    public $goButtonLable;

    public $goPageLabelOptions;

    public $goButtonLableOptions;

    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, $this->disableCurrentPageButton && $i == $currentPage, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }


        // total page
        if ($this->totalPageLable) {
            $buttons[] = Html::tag('li',Html::a(str_replace('x',$pageCount,$this->totalPageLable),'javascript:return false;',[]),[]);
        }

        //gopage
        if ($this->goPageLabel) {
            $input = Html::input('number',$this->pagination->pageParam,$currentPage+1,array_merge([
                'min' => 1,
                'max' => $pageCount,
                'style' => 'height:34px;width:80px;display:inline-block;margin:0 3px 0 3px',
                'class' => 'form-control',
            ],$this->goPageLabelOptions));

            $buttons[] = Html::tag('li',$input,[]);
        }

        // gobuttonlink
        if ($this->goPageLabel) {
            $buttons[] = Html::submitInput($this->goButtonLable ? $this->goButtonLable : '跳转',array_merge([
                'style' => 'height:34px;display:inline-block;',
                'class' => 'btn btn-primary'
            ],$this->goButtonLableOptions));
        }


        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        return Html::tag($tag, implode("\n", $buttons), $options);
    }
}