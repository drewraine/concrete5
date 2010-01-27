<?
defined('C5_EXECUTE') or die(_("Access Denied."));
class DashboardSitemapExploreController extends Controller {

	public function view($nodeID = 1) {
		$dh = Loader::helper('concrete/dashboard/sitemap');
		if ($dh->canRead()) { 
			$this->set('nodeID', $nodeID);
			$this->addHeaderItem(Loader::helper('html')->css('ccm.sitemap.css'));
			$this->addHeaderItem(Loader::helper('html')->javascript('ccm.sitemap.js'));
			
			$nodes = $dh->getSubNodes($nodeID, 1, false, false);
			$this->set('listHTML', $dh->outputRequestHTML('explore', $nodes));
		}
		$this->set('dh', $dh);
	}
	
	
}

?>