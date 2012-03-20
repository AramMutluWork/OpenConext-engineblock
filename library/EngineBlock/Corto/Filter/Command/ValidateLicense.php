<?php
/**
 * SURFconext EngineBlock
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext EngineBlock
 * @package
 * @copyright Copyright © 2010-2011 SURFnet SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

/**
 * Here we call the external ValidationManager to check what the license information is. An extra SAML response
 * attribute is added to inform the SP what the license status is.
 */
class EngineBlock_Corto_Filter_Command_ValidateLicense extends EngineBlock_Corto_Filter_Command_Abstract
{
    const URN_OID_COLLAB_PERSON_ID  = 'urn:oid:1.3.6.1.4.1.1076.20.40.40.1';

    /**
     * This command may modify the response attributes
     *
     * @return array
     */
    public function getResponseAttributes()
    {
        return $this->_responseAttributes;
    }

    public function execute()
    {
        $config = EngineBlock_ApplicationSingleton::getInstance()->getConfiguration();
        $licenseEngine = new EngineBlock_LicenseEngine_ValidationManager($config);
        $licenseCode = $licenseEngine->validate(
            $this->_responseAttributes[self::URN_OID_COLLAB_PERSON_ID][0],
            $this->_spMetadata,
            $this->_idpMetadata
        );

        $this->_responseAttributes[EngineBlock_LicenseEngine_ValidationManager::LICENSE_SAML_ATTRIBUTE] = array(
            $licenseCode
        );
    }
}