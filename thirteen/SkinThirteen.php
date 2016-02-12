<?php

class SkinThirteen extends SkinTemplate
{
        var $useHeadElement = true;

        function initPage(OutputPage $out)
        {
                parent::initPage($out);

                // skin details
                $this->skinname  = 'Thirteen';
                $this->stylename = 'Thirteen';
                $this->template  = 'ThirteenTemplate';
        }

        function setupSkinUserCss(OutputPage $out)
        {
                global $wgHandheldStyle;

                parent::setupSkinUserCss($out);

                // append style links
                $out->addStyle('Thirteen/main.css', 'screen');
                $out->addStyle('Thirteen/print.css', 'print');
        }

}

