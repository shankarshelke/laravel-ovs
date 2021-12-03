<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use \App\Http\Controllers\Controller as Controller;
use \App\Models\User;

// use Validator;

class CommonController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	protected $data = [];
	protected $viewdata = [];
	protected $user;
	protected $request;


	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	// public function initController(Request $request)
	// {
	// 	// $this->data['plugins'] = [SWEET_ALERT, PNOTIFY];
	// 	// $this->session = \Config\Services::session();
	// 	// $this->validation = \Config\Services::validation();
	// 	// $this->configIonAuth = config('IonAuth');
	// 	// $this->baseModel = new \App\Models\BaseModel();

	// 	// $this->ionAuth    = new \App\Libraries\IonAuth();
	// 	// $user = $this->ionAuth->user()->rowArray();
	// 	// $this->data['user'] = $this->user = new \App\Entities\User($user);

	// 	$this->post = $request->getPost();
	// }

	
	public function __construct($request = null){
		$this->request = $request;
		$this->data['admin_url'] = $admin_url = '/admin';
		// $this->category = new \App\Entities\Category();
		// $this->data['page'] = CATEGORY;
		// $this->data['errors'] = null;
		// $this->data['user'] = new User();
		// $this->post = $this->request->all();
		// $this->data['plugins'] = [SWEET_ALERT, PNOTIFY];
		$this->viewdata = [];
	}

	protected function renderPage($load_viewdata = true)
	{
		$this->viewdata = ($this->viewdata) ? $this->viewdata : $this->arr_view_data;
		// $this->request = new Request();
		if ($this->request->ajax()) {
			// dd($this->viewdata);
			// echo json_encode($this->viewdata);
			return response()->json($this->viewdata);
		} else {
			// $this->data['plugins'] = array_merge($this->data['plugins'], [SWITCHERY, SLICK, CORE, SWEET_ALERT]);

			// if ($load_viewdata) {
			// 	if (!isset($this->viewdata['header_html']))
			// 		$this->viewdata['header_html'] = view('admin\header', $this->data);

			// 	if (!isset($this->viewdata['sidebar_html']))
			// 		$this->viewdata['sidebar_html'] = view('admin\sidebar', $this->data);

			// 	if (!isset($this->viewdata['topbar_html']))
			// 		$this->viewdata['topbar_html'] = view('admin\topbar', $this->data);

			// 	if (!isset($this->viewdata['footer_html']))
			// 		$this->viewdata['footer_html'] = view('admin\footer', $this->data);
			// }

			isset($this->viewdata['js_html']) ? $this->getJsHtml(TRUE) : $this->getJsHtml();
			isset($this->viewdata['css_html']) ? $this->getCssHtml(TRUE) : $this->getCssHtml();
			return view('admin.layout.master', $this->viewdata);
		}
	}

	protected function setupFileUpload()
	{
		$params = $this->request->getPost();
		$this->folderPath = 'uploads/';
		$this->folderPath .= ($this->user) ? 'user_'. $this->user->id : 'public';

		$this->userFileType = (isset($params['userFileType'])) ? $params['userFileType'] : "";
		$this->folderPath = $this->folderPath . '/' . $this->userFileType;

		if (!is_dir($this->folderPath)) {
			mkdir('./' . $this->folderPath, 0777, TRUE);
		}
	}

	// File upload using dropzone js
	protected function fileUploadData()
	{
		$this->setupFileUpload();
		if ($file = $this->request->getFile('file')) {
			if ($file->isValid() && !$file->hasMoved()) {
				$newName = 'Img-' . rand(10, 99) . '-' . str_replace(array("-", " "), array("", "-"), date('d-m-yy H-i-s')) . '.' . $file->getClientExtension();;
				$file->move($this->folderPath, $newName);
				$fileData['file_name'] = $newName;
				$fileData['file_path'] = $this->folderPath;
				$fileData['file_full_path'] = url('/'). $this->folderPath . '/' . $newName;
				return $fileData;
			}
		}
	}

	public function resizeImg($config) //params:- source_image,width,height,new_image,create_thumb
	{
		if (!isset($config['width']))
			$config['width']  = 60;
		if (!isset($config['height']))
			$config['height'] = 60;

		$config['create_thumb'] = (isset($config['create_thumb'])) ? (isset($config['create_thumb'])) : FALSE;

		if (isset($config['new_image_path']))
			$expenses_large_path = $config['new_image_path'];
		else
			$expenses_large_path = 'uploads/temp';

		if (!is_dir($expenses_large_path)) {
			mkdir($expenses_large_path, 0777, TRUE);
		}

		if (!isset($config['new_image'])) {
			$folder_path_array = explode('/', $config['source_image']);
			$config['new_image'] = $expenses_large_path . '/' . end($folder_path_array);
		}

		$config['maintain_ratio'] = (isset($config['maintain_ratio'])) ? $config['maintain_ratio'] : TRUE;

		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$this->image_lib->clear();
		$this->image_lib->initialize($config);
		if ($this->image_lib->resize()) {
			return $resize_img_path = $config['new_image'];
		} else {
			return false;
		}
	}

	protected function getJsHtml($marge = false)
	{
		$js_html = '';
		if (isset($this->data['plugins']))
			foreach ($this->data['plugins'] as $key => $plugin) {
				switch ($plugin) {
					case EDITOR:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/summernote/summernote-bs4.min.js') . '"></script>';
						break;
					case DROPZONE:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/dropzone/dist/dropzone.js') . '"></script>';
						break;
					case DATATABLE:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/jquery.dataTables.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/dataTables.bootstrap4.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/dataTables.buttons.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/buttons.bootstrap4.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/jszip.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/pdfmake.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/vfs_fonts.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/buttons.html5.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/buttons.print.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/buttons.colVis.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/dataTables.responsive.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/datatables/responsive.bootstrap4.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/js/custom/custom-table-datatable.js') . '"></script>';
						break;
					case SWITCHERY:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/switchery/switchery.min.js') . '"></script>';
						break;
					case SWEET_ALERT:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/sweet-alert2/sweetalert2.min.js') . '"></script>';
						break;
					case PNOTIFY:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/pnotify/js/pnotify.custom.min.js') . '"></script>';
						break;
					case SLICK:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/slick/slick.min.js') . '"></script>';
						break;
					case INPUTMASK:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/bootstrap-inputmask/jquery.inputmask.bundle.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/js/custom/custom-form-inputmask.js') . '"></script>';
						break;
					case TOOLBAR:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/toolbar/jquery.toolbar.min.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/js/custom/custom-toolbar.js') . '"></script>';
						break;
					case CROPPE:
						$js_html .= '<script src="' . url('/assets/admin/main/plugins/croppie-master/croppie.js') . '"></script>';
						// $js_html .= '<script src="' . url('/assets/admin/main/plugins/cropperjs/cropper.js') . '"></script>';
						// $js_html .= '<script src="' . url('/assets/admin/main/plugins/cropperjs/main.js') . '"></script>';
						break;
					case CORE:
						$js_html .= '<script src="' . url('/assets/admin/main/js/vertical-menu.js') . '"></script>';
						$js_html .= '<script src="' . url('/assets/admin/main/js/core.js') . '"></script>';
						break;
					default:
						null;
						break;
				}
			}

		if (isset($this->data['page']))
			switch ($this->data['page']) { //PAGES JS
				case PRODUCTS:
					$js_html .= '<script src="' . url('/assets/admin/main/js/custom/custom-ecommerce-product-detail-page.js') . '"></script>';
					break;
				case USER:
					$js_html .= '<script src="' . url('/assets/admin/custom/js/user-page.js') . '"></script>';
					break;
				default:
					break;
			}

		if ($marge)
			$this->viewdata['js_html'] .= $js_html;
		else
			$this->viewdata['js_html'] = $js_html;
	}

	protected function getCssHtml($marge = false)
	{
		$css_html = '';
		if (isset($this->data['plugins']))
			foreach ($this->data['plugins'] as $key => $plugin) {
				switch ($plugin) {
					case EDITOR: //Summernote css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/summernote/summernote-bs4.css') . '" rel="stylesheet" type="text/css">';
						break;
					case DROPZONE: //Dropzone css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/dropzone/dist/dropzone.css') . '" rel="stylesheet" type="text/css">';
						break;
					case DATATABLE: //Datatable css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/datatables/dataTables.bootstrap4.min.css') . '" rel="stylesheet" type="text/css">';
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/datatables/buttons.bootstrap4.min.css') . '" rel="stylesheet" type="text/css">';
						//Responsive Datatable css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/datatables/responsive.bootstrap4.min.css') . '" rel="stylesheet" type="text/css">';
						break;
					case SWITCHERY: //SWITCHERY css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/switchery/switchery.min.css') . '" rel="stylesheet" type="text/css">';
						break;
					case SWEET_ALERT: //PNOTIFY css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/sweet-alert2/sweetalert2.min.css') . '" rel="stylesheet" type="text/css">';
						break;
					case PNOTIFY: //PNOTIFY css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/pnotify/css/pnotify.custom.min.css') . '" rel="stylesheet" type="text/css">';
						break;
					case SLICK: //SLICK css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/slick/slick.css') . '" rel="stylesheet" type="text/css">';
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/slick/slick-theme.css') . '" rel="stylesheet" type="text/css">';
						break;
					case INPUTMASK: //INPUTMASK css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/pnotify/css/pnotify.custom.min.css') . '" rel="stylesheet" type="text/css">';
						break;
					case TOOLBAR: //INPUTMASK css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/toolbar/jquery.toolbar.css') . '" rel="stylesheet" type="text/css">';
						break;
					case CROPPE: //INPUTMASK css
						$css_html .= '<link href="' . url('/assets/admin/main/plugins/croppie-master/croppie.css') . '" rel="stylesheet" type="text/css">';
						// $css_html .= '<link href="' . url('/assets/admin/main/plugins/cropperjs/cropper.css') . '" rel="stylesheet" type="text/css">';
						// $css_html .= '<link href="' . url('/assets/admin/main/plugins/cropperjs/main.css') . '" rel="stylesheet" type="text/css">';
						break;

					default:
						null;
						break;
				}
			}

		if ($marge)
			$this->viewdata['css_html'] .= $css_html;
		else
			$this->viewdata['css_html'] = $css_html;
	}


	protected function sendText($params)
	{
		$message = $params['otp'] . ' this is the verify otp';
		$numbers = array($params['contact']);

		// try {
		// 	$result = sendSMS($numbers, $message);
		// 	return true;
		// } catch (Exception $e) {
		// 	die('Error: ' . $e->getMessage());
		// }
	}

	protected function sendOtp($id, $params)
	{
		$params['otp'] = rand(100000, 999999);

		$this->ionAuth->update($id, ['otp' => $params['otp']]);

		$params = array_merge($this->ionAuth->user($id)->rowArray(), $params);

		// print_r($params);exit();

		if ((isset($params['sendOtp']) && $params['sendOtp'] == "contact") || !isset($params['sendOtp'])) {
			// $sendOtp['text'] = $this->sendText($params);
			$sendOtp['text'] = true;
		}

		// if((isset($params['sendOtp']) && $params['sendOtp'] == "email") || (!isset($params['sendOtp']) && $params['email'] != NULL)){
		// 	$sendOtp['email'] = $this->sendEmail($params);
		// }

		$sendOtp['params'] = $params;

		return $sendOtp;
	}


	protected function sendEmailVerify()
	{
		$this->load->helper('general');
		$this->load->model('email_model');
		$this->config->load('email');

		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'shivadreamsofficial@gmail.com',
			'smtp_pass' => '',
			'mailtype'  => 'html',
			'charset' => 'utf-8',
			'wordwrap' => TRUE
		);

		$this->load->library('email', $config);

		$cmp_name = htmlentities($this->data['cmpName'], ENT_QUOTES);
		$from = array('email' => 'shivadreamsofficial@gmail.com', 'name' => "shivadreams");
		$to = $this->post['toEmails'];
		$cc = $this->post['ccEmails'];
		$subject = (isset($this->post['isCreditNote']) && $this->post['isCreditNote'] ? 'Refund Receipt for ' : 'Payment Receipt for ') . htmlentities($this->post['inIdString'], ENT_QUOTES);
		$message = $this->load->view('templates/email/pmt_receipt', $this->post, true);
		$email_send_params = array('from' => $from, 'to' => $to, 'subject' => $subject, 'message' => $message, 'email_obj' => $this->email, 'reply_to' => NULL, 'cc' => $cc);
		$result = $this->email_model->emailSend($email_send_params);
		return $result;
	}
}
