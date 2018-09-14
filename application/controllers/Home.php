<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper("url");//BORRAR CACHÉ DE LA PÁGINA
		$this->load->model('M_Datos');
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
	}

	public function index()
	{
		$this->load->view('v_home');
	}

	function register(){
		$data['error'] = EXIT_ERROR;
      	$data['msj']   = null;
		try {
			$name           = $this->input->post('Name');
			$surname 		= $this->input->post('Surname');
			$correo 		= $this->input->post('Email');
			$telefono	    = $this->input->post('Phone');
			$empresa 		= $this->input->post('Company');
			$cargo 		    = $this->input->post('Position');
			$city           = $this->input->post('City');
			$pais	 		= $this->input->post('Country');
			$existe         = $this->M_Datos->existCorreo($correo);
			$fecha          = date('Y-m-d');
			if(count($existe) != 0) {
				$data['msj']   = 'Correo ya registrado';
			}
			else{
				$insertParticipante = array('nombre'      => $name,
										   'apellido'     => $surname,
										   'email' 	      => $correo,
										   'telefono' 	  => $telefono,
										   'empresa'      => $empresa,
										   'cargo'        => $cargo,
										   'ciudad'       => $city,
										   'pais'         => $pais,
										   'fecha'        => $fecha);
				$datoInsert  = $this->M_Datos->insertarDatos($insertParticipante,'participante');
				$this->sendConfirmation($correo);
	          	$data['msj']   = $datoInsert['msj'];
	          	$data['error'] = $datoInsert['error'];
	          }
		} catch(Exception $ex) {
			$data['msj'] = $ex->getMessage();
		}
      	echo json_encode($data);
	}
	function sendConfirmation($correo){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {  
			$this->load->library("email");
			$configGmail = array('protocol'  => 'smtp',
			                     'smtp_host' => 'smtpout.secureserver.net',
			                     'smtp_port' => 3535,
			                     'smtp_user' => 'info@marketinghpe.com',
			                     'smtp_pass' => 'h#120918Pe',
			                     'mailtype'  => 'html',
			                     'charset'   => 'utf-8',
			                     'newline'   => "\r\n");    
			$this->email->initialize($configGmail);
			$this->email->from('info@marketinghpe.com');
			$this->email->to($correo);
			// $this->email->to('jose.minayac15@gmail.com');
			$this->email->subject('Gracias por registrarse al Taller de entrenamiento técnico de HPE SimpliVity');
			$texto = '<!DOCTYPE html>
<html>
    <body>
        <table width="500px" cellpadding="0" cellspacing="0" align="center" style="border: solid 1px #ccc;">
            <tr>
                <td>
                    <table width="400" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 30px 0">
                        <tr>
                            <td style="text-align: center;padding: 0;margin: 0;padding-top: 20px; padding-bottom: 10px"><font style="font-family: arial;color: #000000;font-size: 18px;font-weight: 600">Muchas gracias por confirmar <br>
                          su participaci&oacute;n. Su registro <br>
                          se realiz&oacute; con éxito.</font></td>
                        </tr>                                       <tr>
                            <td style="text-align: center;padding: 0;margin: 0;padding-bottom: 20px"><font style="font-family: arial;color: #000000;font-size: 16px;font-weight: 200">Lo esperamos</font></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>';
			$this->email->message($texto);
			$this->email->send();
			$data['error'] = EXIT_SUCCESS;
		}catch (Exception $e){
			$data['msj'] = $e->getMessage();
		}
		return json_encode(array_map('utf8_encode', $data));
	}
}