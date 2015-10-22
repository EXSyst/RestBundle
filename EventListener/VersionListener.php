<?php

/*
 * This file is part of the ApiBundle package.
 *
 * (c) EXSyst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EXSyst\Bundle\ApiBundle\EventListener;

use EXSyst\Component\Api\Version\VersionResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VersionListener implements EventSubscriberInterface
{
    /**
     * @var VersionResolverInterface
     */
    private $versionResolver;
    /**
     * @var string
     */
    private $attributeName;
    /**
     * @var scalar
     */
    private $defaultVersion;

    /**
     * @param VersionResolverInterface $versionResolver
     * @param string                   $attributeName
     */
    public function __construct(VersionResolverInterface $versionResolver, $attributeName)
    {
        $this->versionResolver = $versionResolver;
        $this->attributeName = $attributeName;
    }

    /**
     * Sets the default version.
     *
     * @param scalar $defaultVersion
     */
    public function setDefaultVersion($defaultVersion)
    {
        $this->defaultVersion = $defaultVersion;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $version = $this->versionResolver->resolve($request) ?: $this->defaultVersion;
        if (null !== $version) {
            $request->attributes->set($this->attributeName, $version);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 33]],
        ];
    }
}
