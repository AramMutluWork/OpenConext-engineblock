<?php

namespace OpenConext\EngineBlockFunctionalTestingBundle\Mock;

use OpenConext\EngineBlockFunctionalTestingBundle\Saml2\AuthnRequest;
use SAML2\AuthnRequest as SAMLAuthnRequest;
use SAML2\XML\md\SPSSODescriptor;

/**
 * Class MockServiceProvider
 * @package OpenConext\EngineBlockFunctionalTestingBundle\Mock
 * @SuppressWarnings("PMD")
 */
class MockServiceProvider extends AbstractMockEntityRole
{
    public function loginUrl()
    {
        return $this->loginUrlRedirect();
    }

    public function loginUrlRedirect()
    {
        return $this->descriptor->Extensions['LoginRedirectUrl'];
    }

    public function loginUrlPost()
    {
        return $this->descriptor->Extensions['LoginPostUrl'];
    }

    public function assertionConsumerServiceLocation()
    {
        /** @var SPSSODescriptor $role */
        $role = $this->getSsoRole();
        return $role->AssertionConsumerService[0]->Location;
    }

    /**
     * @return AuthnRequest
     */
    public function getAuthnRequest()
    {
        return $this->descriptor->Extensions['SAMLRequest'];
    }

    public function setAuthnRequest(SAMLAuthnRequest $authnRequest)
    {
        $this->descriptor->Extensions['SAMLRequest'] = $authnRequest;
        return $this;
    }

    public function useIdpTransparently($entityId)
    {
        $this->descriptor->Extensions['TransparentIdp'] = $entityId;
        return $this;
    }

    public function getTransparentIdp()
    {
        return isset($this->descriptor->Extensions['TransparentIdp']) ?
            $this->descriptor->Extensions['TransparentIdp'] :
            '';
    }

    public function useUnsolicited()
    {
        $this->descriptor->Extensions['Unsolicited'] = true;
        return $this;
    }

    public function mustUseUnsolicited()
    {
        return isset($this->descriptor->Extensions['Unsolicited']) && $this->descriptor->Extensions['Unsolicited'];
    }

    public function signAuthnRequests()
    {
        /** @var SPSSODescriptor $role */
        $role = $this->getSsoRole();
        $role->AuthnRequestsSigned = true;
        return $this;
    }

    public function mustSignAuthnRequests()
    {
        /** @var SPSSODescriptor $role */
        $role = $this->getSsoRole();
        return $role->AuthnRequestsSigned;
    }

    public function useHttpPost()
    {
        $this->descriptor->Extensions['UsePost'] = true;
        return $this;
    }

    public function mustUsePost()
    {
        return isset($this->descriptor->Extensions['UsePost']);
    }

    public function useHttpRedirect()
    {
        unset($this->descriptor->Extensions['UsePost']);
        return $this;
    }

    public function isTrustedProxy()
    {
        return isset($this->descriptor->Extensions['TrustedProxy']) && $this->descriptor->Extensions['TrustedProxy'];
    }

    public function trustedProxy()
    {
        $this->descriptor->Extensions['TrustedProxy'] = true;
        return $this;
    }

    public function setAuthnRequestProxyCountTo($proxyCount)
    {
        $this->descriptor->Extensions['SAMLRequest']->setProxyCount($proxyCount);
        return $this;
    }

    public function setAuthnRequestToPassive()
    {
        $this->descriptor->Extensions['SAMLRequest']->setIsPassive(true);
    }

    protected function getRoleClass()
    {
        return SPSSODescriptor::class;
    }
}
