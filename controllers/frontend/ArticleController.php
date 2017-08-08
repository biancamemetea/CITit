<?php
/**
 * DotBoost Technologies Inc.
 * DotKernel Application Framework
 *
 * @category   DotKernel
 * @package    Frontend
 * @copyright  Copyright (c) 2009-2015 DotBoost Technologies Inc. (http://www.dotboost.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version    $Id: PageController.php 867 2015-01-03 22:52:05Z julian $
*/

/**
 * Page Controller
 * @author     DotKernel Team <team@dotkernel.com>
 */

$articleView = new Article_View($tpl);
$articleModel = new Article();
$session = Zend_Registry::get('session');
// all actions MUST set  the variable  $pageTitle
$pageTitle = $option->pageTitle->action->{$registry->requestAction};
switch ($registry->requestAction)
{
	default:
	case 'show_articles':
		$articleData = $articleModel->getAllArticleData();
		$articleView->showArticles('show_articles', $articleData);
		break;

	case 'show_article_content':

		$articleData = $articleModel->getSingleArticleData($registry->request['id']);
		$articleCommentAndReply = $articleModel->getCommentByArticleId($registry->request['id']);
		$articleView->showSingleArticle('show_article_content', $articleData, $articleCommentAndReply);

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			var_dump($_POST);
			$postId = (int)$_POST['id'];
			$commentAuthorId = $articleModel->checkCommentPosterByCommentId($postId);
			$uidFromSession = $session->user->id;

	        if (isset($_POST['delete']) && ($uidFromSession == $commentAuthorId)) {
	            $articleModel->deleteCommentById($postId);
	            echo json_encode(['content' => '[deleted]']);
	        } elseif (isset($_POST['content']) && ($uidFromSession == $commentAuthorId)) {
	        	$articleModel->commentDatabaseWork($_POST['content'], $_POST['id']);
	        }

	        exit;

	    }
		break;
}