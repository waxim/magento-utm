<?php

class AlanCole_UtmTag_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
		
		$url = Mage::helper("adminhtml")->getUrl("utmtagger/index/post");
		$key = Mage::getSingleton('core/session')->getFormKey();
		
        $block = $this->getLayout()
        ->createBlock('core/text', 'example-block')
        ->setText('<form method="post" action="'.$url.'"><input type="hidden" name="form_key" value="'.$key.'" /><div class="content-header"><h3 class="icon-head head-cms-page">Google Analytics UTM Tagger</h3><p class="form-buttons">
			<button id="id_4d116d21af23439f15fc6fb928ca8669" title="Back" class="scalable save" type="submit" style=""><span><span><span>Save URL</span></span></span></button></div><p>This feature will add a URL Rewrite to Magento and append UTM Tracking information to the resulting page.</p><div class="entry-edit"> 	<div class="entry-edit"> 		<div class="entry-edit-head"> 			<h4 class="icon-head head-edit-form fieldset-legend">URL Information</h4> 			<div class="form-buttons"></div> 		</div> 		<div class="fieldset" id="page_base_fieldset"> 			<div class="hor-scroll"> 				<table cellspacing="0" class="form-list"> 					<tbody> 						<tr> 							<td class="label"> 								<label for="page_title">URL Code <span class="required">*</span></label> 							</td> 							<td class="value"> 								<input id="url_code" name="code" value="" title="URL Code" type="text" class="input-text required-entry"> 								<p class="note" id="note_identifier"><span>This is the code you like in the url http://example.com/<strong>code</strong></span></p> 							</td> 						</tr> 						<tr> 							<td class="label"> 								<label for="page_title">Destination <span class="required">*</span></label> 							</td> 							<td class="value"> 								<input id="destination" name="destination" value="" title="destination" type="text" class="input-text required-entry"> 								<p class="note" id="note_identifier"><span>This is the page you\'d like the link to land on.</span></p> 							</td> 						</tr> 						<tr> 							<td class="label"> 								<label for="page_title">Campaign Source</label> 							</td> 							<td class="value"> 								<input id="cs" name="cs" value="" title="Campaign Source" type="text" class="input-text"> 								<p class="note" id="note_identifier"><span>(referrer: google, citysearch, newsletter4)</span></p> 							</td> 						</tr> 						<tr> 							<td class="label"> 								<label for="page_title">Campaign Name</label> 							</td> 							<td class="value"> 								<input id="cn" name="cn" value="" title="Campaign Name" type="text" class="input-text"> 								<p class="note" id="note_identifier"><span>The name of your campaign.</span></p> 							</td> 						</tr> 						<tr> 							<td class="label"> 								<label for="page_title">Campaign Medium</label> 							</td> 							<td class="value"> 								<input id="cm" name="cm" value="" title="Campaign Medium" type="text" class="input-text"> 								<p class="note" id="note_identifier"><span>The type of media related to this link, e.g. web, email, print.</span></p> 							</td> 						</tr> 						<tr> 							<td class="label"> 								<label for="page_title">Campaign Content</label> 							</td> 							<td class="value"> 								<input id="cc" name="cc" value="" title="Campaign Content" type="text" class="input-text"> 							</td> 						</tr> 						<tr> 							<td class="label"> 								<label for="page_title">Campaign Term</label> 							</td> 							<td class="value"> 								<input id="ct" name="ct" value="" title="Campaign Term" type="text" class="input-text"> 							</td> 						</tr> 					</tbody> 				</table> 			</div> 		</div> 	</div> </div> </form>');
		
        $this->_addContent($block);

        $this->renderLayout();
    }
	
	public function postAction(){

		$code = $this->getRequest()->getPost('code');
		$destination = $this->getRequest()->getPost('destination');
		$medium = $this->getRequest()->getPost('cm');
		$name = $this->getRequest()->getPost('cn');;
		$source = $this->getRequest()->getPost('cs');;
		$content = $this->getRequest()->getPost('cc');
		$term = $this->getRequest()->getPost('ct');
		
		if($code && $destination){
			
			$params = array();
			if($medium){ $params['utm_medium'] = $medium; }
			if($name){ $params['utm_campaign'] = $name; }
			if($source){ $params['utm_source'] = $source; }
			if($term){ $params['utm_term'] = $term; }
			if($content){ $params['utm_content'] = $content; }
			
			$destination .= "?" . http_build_query($params);
			
			Mage::getModel('core/url_rewrite')->setIsSystem(0)->setOptions('R')->setIdPath($code)->setTargetPath($destination)->setRequestPath($code)->save();
			Mage::getSingleton('adminhtml/session')->addSuccess('Great news, that url has been added.'); 
		} else {
			Mage::getSingleton('adminhtml/session')->addError('Sorry, we need at least a url code and destination to add a rewrite.'); 
		}
		
		$this->_redirect('utmtagger/index/index');
		return $this;
	}
	
}