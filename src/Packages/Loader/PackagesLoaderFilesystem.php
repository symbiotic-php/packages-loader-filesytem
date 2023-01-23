<?php

declare(strict_types=1);

namespace Symbiotic\Packages\Loader;

use Symbiotic\Core\Support\Arr;
use Symbiotic\Packages\PackagesLoaderInterface;
use Symbiotic\Packages\PackagesRepositoryInterface;


class PackagesLoaderFilesystem implements PackagesLoaderInterface
{
    /**
     * PackagesLoader constructor.
     *
     * @param array $scan_dirs
     * @param int   $max_depth
     */
    public function __construct(protected array $scan_dirs = [], protected int $max_depth = 3)
    {
    }

    /**
     * @inheritDoc
     *
     * @param PackagesRepositoryInterface $repository
     *
     * @return void
     * @throws \Exception
     */
    public function load(PackagesRepositoryInterface $repository): void
    {
        $packages = [];
        if (!empty($this->scan_dirs)) {
            foreach ($this->scan_dirs as $dir) {
                if (is_dir($dir) && is_readable($dir)) {
                    $packages = array_merge($packages, $this->getDirPackages($dir));
                } else {
                    throw new \Exception('Directory [' . $dir . '] is not readable or not exists!');
                }
            }
            foreach ($packages as $v) {
                $repository->addPackage($v);
            }
        }
    }

    /**
     * @param string $dir
     *
     * @return array
     */
    protected function getDirPackages(string $dir): array
    {
        $files = $packages = [];

        for ($i = 0; $i < $this->max_depth; $i++) {
            $level = str_repeat('/*', $i);
            $files = array_merge($files, glob($dir . $level . '/composer.json', GLOB_NOSORT));
            $files = array_merge($files, glob($dir . $level . '/symbiotic.json', GLOB_NOSORT));
        }

        foreach ($files as $file) {
            if (\is_readable($file)) {
                if(str_ends_with($file,'symbiotic.json')) {
                    $config = @\json_decode(file_get_contents($file), true);
                } else {
                    $config = Arr::get(@\json_decode(file_get_contents($file), true) ?? [], 'extra.symbiotic');
                }

                if (is_array($config)) {
                    $app = Arr::get($config, 'app');
                    $config['base_path'] = dirname($file);
                    if (is_array($app)) {
                        $app['base_path'] = $config['base_path'];
                        $config['app'] = $app;
                    }
                    $packages[] = $config;
                }
            }
        }
        return $packages;
    }
}
