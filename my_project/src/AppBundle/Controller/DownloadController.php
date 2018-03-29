<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DownloadController extends Controller
{
    /**
     * @Route("/download", name="download_game_index")
     */
    public function indexAction()
    {
    	return $this->render('default/download.html.twig');	
	}

	/**
	* @Route("/download/game", name="download_game")
	*/
	public function downloadAction(Request $request){

		$filepath = $this->getParameter('download_directory').'4K.mp4';

    	if (file_exists($filepath))
    	{
    		$response = new StreamedResponse(
    			function() use ($filepath){
    				$file;
    				if ($file = fopen($filepath, 'r'))
    				{
    					$chunksize = 1024*1024;
    					$bytes_send = 0;
    					$new_length = filesize($filepath);
    					while(!feof($file) &&
    						(!connection_aborted()) &&
    						($bytes_send<$new_length)
    					)
    					{
    						$buffer = fread($file, $chunksize);
    						echo($buffer);
    						$bytes_send += strlen($buffer);
    					}
    					fclose($file);
    				}
    			}
    		);

    		$response->headers->set('Content-type', 'application/octet-stream');
    		$response->headers->set('Content-Length',filesize($filepath));
    		$contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, '4K.mp4');
    		$response->headers->set("Content-Disposition", $contentDisposition);
    		$response->headers->set('Content-Transfer-Encoding', 'binary');
    		$response->prepare($request);

    		return $response;
    	}

		return new Response('Error');
	}
}
