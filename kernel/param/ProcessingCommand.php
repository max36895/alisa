<?php
/*
 * result
 * 1 - ответ; 2 -  текст на кнопке; 3 - url
 * */

namespace bot\kernel\param;

use bot\processing\Command;

class ProcessingCommand extends Command
{
    public function commands($index)
    {
        return ['Пример текста'];
    }

    public function isParams()
    {
        return true;
    }
}
