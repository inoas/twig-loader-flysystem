<?php

namespace CedricZiel\TwigLoaderFlysystem;

use League\Flysystem\Filesystem;
use Twig_Error_Loader;
use Twig_LoaderInterface;

/**
 * Provides a template loader for twig that allows to use flysystem
 * instances to load templates.
 *
 * @package CedricZiel\TwigLoaderFlysystem
 */
class FlysystemLoader implements Twig_LoaderInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FlysystemLoader constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSource($name)
    {
        $this->checkTemplateExistsAndIsFileOrFail($name);

        return $this->filesystem->read($name);
    }

    /**
     * Checks if the underlying flysystem contains a file of the given name.
     *
     * @param string $name
     *
     * @throws Twig_Error_Loader
     */
    protected function checkTemplateExistsAndIsFileOrFail($name)
    {
        if (!$this->filesystem->has($name)) {
            throw new Twig_Error_Loader('Template could not be found on the given filesystem');
        }

        if ($this->filesystem->get($name)->isDir()) {
            throw new Twig_Error_Loader('Cannot use directory as template');
        }
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name)
    {
        $this->checkTemplateExistsAndIsFileOrFail($name);

        return $name;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string $name The template name
     * @param int    $time Timestamp of the last modification time of the
     *                     cached template
     *
     * @return bool true if the template is fresh, false otherwise
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time)
    {
        $this->checkTemplateExistsAndIsFileOrFail($name);
        // TODO: Implement isFresh() method.
    }
}
