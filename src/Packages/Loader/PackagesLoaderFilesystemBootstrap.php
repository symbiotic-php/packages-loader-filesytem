<?php

declare(strict_types=1);

namespace Symbiotic\Packages\Loader;

use Symbiotic\Container\DIContainerInterface;
use Symbiotic\Core\BootstrapInterface;
use Symbiotic\Packages\PackagesRepositoryInterface;


class PackagesLoaderFilesystemBootstrap implements BootstrapInterface
{

    public function bootstrap(DIContainerInterface $core): void
    {
        /**
         *  Add filesystem packages loader
         */
        $core->afterResolving(
            PackagesRepositoryInterface::class,
            function (PackagesRepositoryInterface $repository) use ($core) {
                $repository->addPackagesLoader(
                    new PackagesLoaderFilesystem($core->get('config::packages_paths'))
                );
            }
        );
    }
}
