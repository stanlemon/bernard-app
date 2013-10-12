<?php
namespace Lemon\BernardBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lemon\BernardBundle\DependencyInjection\Compiler\MessageCompilerPass;

class LemonBernardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MessageCompilerPass());
    }
}
