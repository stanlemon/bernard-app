<?php
namespace Lemon\BernardBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lemon\BernardBundle\DependencyInjection\Compiler\MessageServiceCompilerPass;
use Lemon\BernardBundle\DependencyInjection\Compiler\ServiceSetupCompilerPass;

class LemonBernardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ServiceSetupCompilerPass());
        $container->addCompilerPass(new MessageServiceCompilerPass());
    }
}
