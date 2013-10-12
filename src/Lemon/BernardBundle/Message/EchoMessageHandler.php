<?php
namespace Lemon\BernardBundle\Message;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\ArgvInput;
use Bernard\Message\DefaultMessage;

class EchoMessageHandler
{
    public function echoMessageHandler(DefaultMessage $message)
    {
        echo $message->message . PHP_EOL;
    }
}