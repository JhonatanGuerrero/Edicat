<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewControl = 'Usuarios';
        $this->load->model('Usuarios_model');
        $this->load->model('TiposDocumentos_model');
        $this->load->model('Perfiles_model');
        $this->load->model('Estados_model');
        $this->load->model('Login_model');
        if (!$this->session->userdata('Login')) {
            $this->session->set_flashdata("error", "Debe iniciar sesión antes de continuar. Después irá a: http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI] );
            $url = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
            redirect(site_url("Login/index/" . substr($url, 1)));
        }
    }

    public function index() {
        redirect(site_url('Mantenimiento/' . $this->viewControl . "/Admin/"));
    }

    public function Admin() {
        $idPermiso = 1;
        $page = validarPermisoPagina($idPermiso);

        $dataUsers = $this->Usuarios_model->obtenerUsuariosEP();

        $data = new stdClass();
        $data->Controller = "Usuarios";
        $data->title = "Administración Usuarios";
        $data->subtitle = "Listado de Usuarios";
        $data->contenido = $this->viewControl . '/Admin';
        $data->ListaDatos = $dataUsers;

        $this->load->view('frontend', $data);
    }

    public function Crear() {
        $idPermiso = 2;
        $page = validarPermisoPagina($idPermiso);

        $dataTipDoc = $this->TiposDocumentos_model->obtenerTiposDocumentos();
        if (isset($dataTipDoc) && $dataTipDoc != FALSE) {
            $dataPerfil = $this->Perfiles_model->obtenerPerfiles();
            if (isset($dataPerfil) && $dataPerfil != FALSE) {
                $dataEstado = $this->Estados_model->obtenerEstadosPor('101');
                if (isset($dataEstado) && $dataEstado != FALSE) {
                    $dataAdmin = $this->Usuarios_model->obtenerAdmin();
                    if (isset($dataAdmin) && $dataAdmin != FALSE) {

                        $data = new stdClass();
                        $data->Controller = "Usuarios";
                        $data->title = "Creación de Usuario";
                        $data->subtitle = "Usuario Nuevo";
                        $data->contenido = $this->viewControl . '/Crear';
                        $data->Lista1 = $dataTipDoc;
                        $data->Lista2 = $dataPerfil;
                        $data->Lista3 = $dataEstado;
                        $data->ListaAdmin = $dataAdmin;

                        $this->load->view('frontend', $data);
                    } else {
                        $$this->session->set_flashdata("error", "No se tienen datos sobre 'Administradores.'");
                        redirect(base_url("/Index/"));
                    }
                } else {
                    $$this->session->set_flashdata("error", "No se tienen datos sobre 'Estados.'");
                    redirect(base_url("/Mantenimiento/Estados/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se tienen datos sobre 'Perfiles.'");
                redirect(base_url("/Mantenimiento/Perfiles/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se tienen datos sobre 'Tipos de Documentos.'");
            redirect(base_url("/Mantenimiento/TiposDocumentos/Admin/"));
        }
    }

    public function encriptarPass($pass) {
        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
        $salt = base64_encode($salt);
        $salt = str_replace('+', '.', '$2y$10$' . $salt . '$');
        $hash = crypt($pass, $salt);

        $password = array(
            'salt' => $salt,
            'pass' => $hash
        );

        return $password;
    }

    public function encriptarPassSalt($pass, $salt) {
        $hash = crypt($pass, $salt);

        $password = array(
            'salt' => $salt,
            'pass' => $hash
        );

        return $password;
    }

    public function Log($usuario) {
        if (isset($usuario)) {
            redirect(base_url("/Mantenimiento/Log/Usuarios/" . $usuario . "/"));
        } else {
            $this->session->set_flashdata("error", "No se puede acceder al Log del Usuario");
            redirect(base_url() . "/Mantenimiento/Usuarios/Admin/");
        }
    }

    public function NewUser() {
        $usuario = $this->input->post("user_user");
        $password1 = $this->input->post("user_pass1");
        $password2 = $this->input->post("user_pass2");
        $nombre = $this->input->post("user_name");
        $tipoDocumento = $this->input->post("user_tipdoc");
        $documento = $this->input->post("user_docu");
        $perfil = $this->input->post("user_perf");
        $estado = $this->input->post("user_est");
        $cambioPass = $this->input->post("user_camPass");
        $Administrador = $this->input->post("Administrador");
        if ($Administrador == "") {
            $Administrador = 100;
        }

        $user = $this->Usuarios_model->obtenerUsuarioPorUserEP($usuario);
        if (isset($user) && $user == TRUE) {
            echo "El Usuario <b>" . $user[0]['Usuario'] . "</b> ya existe en la base de datos, y está " . $user[0]['Estado'] . ". No se puede crear.";
        } else {
            $p = $this->encriptarPass($password2);
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $data = array(
                'Codigo' => null,
                'Usuario' => $usuario,
                'Pass' => $p['pass'],
                'Salt' => $p['salt'],
                'Nombre' => $nombre,
                'TipoDocumento' => $tipoDocumento,
                'Documento' => $documento,
                'Perfil' => $perfil,
                'Administrador' => $Administrador,
                'Estado' => $estado,
                'CambioPass' => $cambioPass,
                'Habilitado' => 1,
                'UsuarioCreacion' => $user,
                'FechaCreacion' => $fecha,
                'UsuarioModificacion' => null,
                'FechaModificacion' => null
            );

            if ($this->Usuarios_model->save($data)) {
                $user = $this->Usuarios_model->obtenerUsuarioPorUserEP($usuario);
                if ($user) {
                    $data['Codigo'] = $user[0]['Codigo'];
                    $modulo = "Usuarios";
                    $tabla = "Usuarios";
                    $accion = "Crear Usuario";
                    $llave = $user[0]['Codigo'];
                    $sql = LogSave($data, $modulo, $tabla, $accion, $llave);

                    echo 1;
                } else {
                    echo "No se pudo guardar, por favor intentelo de nuevo.";
                }
            } else {
                echo "No se pudo guardar, por favor intentelo de nuevo.";
            }
        }
    }

    public function Consultar($usuario) {
        $idPermiso = 3;
        $page = validarPermisoPagina($idPermiso);

        $dataUser = $this->Usuarios_model->obtenerUsuarioPorCodEP($usuario);
        if (isset($dataUser) && $dataUser != FALSE) {
            $dataTipDoc = $this->TiposDocumentos_model->obtenerTiposDocumentos();
            if (isset($dataTipDoc) && $dataTipDoc != FALSE) {
                $dataPerfil = $this->Perfiles_model->obtenerPerfiles();
                if (isset($dataPerfil) && $dataPerfil != FALSE) {
                    $dataEstado = $this->Estados_model->obtenerEstadosPor('101');
                    if (isset($dataEstado) && $dataEstado != FALSE) {

                        $data = new stdClass();
                        $data->Controller = "Usuarios";
                        $data->title = "Consultar de Usuario";
                        $data->subtitle = "Datos del Usuario ";
                        $data->contenido = $this->viewControl . '/Consultar';
                        $data->ListaDatos = $dataUser;
                        $data->Lista1 = $dataTipDoc;
                        $data->Lista2 = $dataPerfil;
                        $data->Lista3 = $dataEstado;

                        $this->load->view('frontend', $data);
                    } else {
                        $$this->session->set_flashdata("error", "No se tienen datos sobre 'Estados.'");
                        redirect(base_url("/Mantenimiento/Perfiles/Admin/"));
                    }
                } else {
                    $this->session->set_flashdata("error", "No se tienen datos sobre 'Perfiles.'");
                    redirect(base_url("/Mantenimiento/Perfiles/Admin/"));
                }
            } else {
                $this->session->set_flashdata("error", "No se tienen datos sobre 'Tipos de Documentos.'");
                redirect(base_url("/Mantenimiento/TiposDocumentos/Admin/"));
            }
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a la información del Usuario <b>" . $usuario . "</b>");
            redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
        };
    }

    public function UpdateUser() {
        $cod = $this->input->post("user_cod");
        $usuario = $this->input->post("user_user");
        $nombre = $this->input->post("user_name");
        $perfil = $this->input->post("user_perf");
        $estado = $this->input->post("user_est");
        $cambioPass = $this->input->post("user_camPass");

        $dataUser = $this->Usuarios_model->obtenerUsuario($cod);
        if (!isset($dataUser) || $dataUser == FALSE) {
            echo "El Usuario indicado no existe en la base de datos. No se puede actualizar.";
        } else {
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $data = array(
                'Usuario' => $usuario,
                'Nombre' => $nombre,
                'Perfil' => $perfil,
                'Estado' => $estado,
                'CambioPass' => $cambioPass,
                'UsuarioModificacion' => $user,
                'FechaModificacion' => $fecha
            );

            if ($this->Usuarios_model->update($cod, $data)) {
                $modulo = "Actualizar Usuarios";
                $tabla = "Usuarios";
                $accion = "Modificar Usuario";
                $data = compararCambiosLog($dataUser, $data);
                //var_dump($data);
                if (count($data) > 2) {
                    $data['Usuario'] = $usuario;
                    $sql = LogSave($data, $modulo, $tabla, $accion, $cod);
                }

                echo 1;
            } else {
                echo "No se pudo guardar, por favor intentelo de nuevo.";
            }
        }
    }

    public function Eliminar($usuario) {
        $idPermiso = 6;
        $page = validarPermisoPagina($idPermiso);

        $dataUser = $this->Usuarios_model->obtenerUsuarioPorCodEP($usuario);
        if (isset($dataUser) && $dataUser != FALSE) {

            $data = new stdClass();
            $data->Controller = "Usuarios";
            $data->title = "Eliminar Usuario";
            $data->subtitle = "Datos del Usuario";
            $data->contenido = $this->viewControl . '/Eliminar';
            $data->ListaDatos = $dataUser;

            $this->load->view('frontend', $data);
        } else {
            $this->session->set_flashdata("error", "No se puede acceder a la información del Usuario <b>" . $usuario . "</b>");
            redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
        };
    }

    public function DeleteUser() {
        $cod = $this->input->post("user_cod");
        $usuario = $this->input->post("user_user");
        $nombre = $this->input->post("user_name");

        $dataUser = $this->Usuarios_model->obtenerUsuario($cod);
        if (!isset($dataUser) || $dataUser == FALSE) {
            echo "El Usuario indicado no existe en la base de datos. No se puede actualizar.";
        } else {
            $user = $this->session->userdata('Usuario');
            $fecha = date("Y-m-d H:i:s");

            $data = array(
                'Estado' => 102,
                'Habilitado' => 0,
                'UsuarioModificacion' => $user,
                'FechaModificacion' => $fecha
            );

            if ($this->Usuarios_model->update($cod, $data)) {
                $modulo = "Eliminar Usuarios";
                $tabla = "Usuarios";
                $accion = "Inhabilitar Usuario";
                $data = compararCambiosLog($dataUser, $data);
                //var_dump($data);
                if (count($data) > 2) {
                    $data['Usuario'] = $usuario;
                    $sql = LogSave($data, $modulo, $tabla, $accion, $cod);
                }

                echo 1;
            } else {
                echo "No se pudo actualizar, por favor intentelo de nuevo.";
            }
        }
    }

    public function Eliminados() {
        $idPermiso = 7;
        $page = validarPermisoPagina($idPermiso);

        $dataUsers = $this->Usuarios_model->obtenerDelUsuariosEP();

        $data = new stdClass();
        $data->Controller = "Usuarios";
        $data->title = "Administración Usuarios";
        $data->subtitle = "Listado de Usuarios";
        $data->contenido = $this->viewControl . '/Eliminados';
        if (isset($dataUsers) && $dataUsers != false) {
            $data->ListaDatos = $dataUsers;
        }

        $this->load->view('frontend', $data);
    }

    public function CambiarPass($usuario) {
        $dataUsers = $this->Usuarios_model->obtenerUsuario($usuario);

        if (isset($dataUsers) && $dataUsers == FALSE) {
            $this->session->set_flashdata("error", "No se puede acceder a la información del Usuario <b>" . $usuario . "</b>");
            redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
        } else {
            $nombre = $dataUsers [0]["Nombre"];
            $user = $dataUsers [0]["Usuario"];

            $data = new stdClass();
            $data->Controller = "Usuarios";
            $data->title = "Cambio de Contraseña";
            $data->subtitle = "Cambio de Contraseña de " . $nombre;
            $data->contenido = $this->viewControl . '/CambiarPass';
            $data->ListaDatos = $dataUsers;
            $data->cod = $usuario;
            $data->user = $user;

            $this->load->view('frontend', $data);
        }
    }

    public function ChangePassUser() {
        $codigo = $this->input->post("codigo");
        $usuario = $this->input->post("usuario");
        $passAct = $this->input->post("passAct");
        $passNew1 = $this->input->post("passNew1");
        $passNew2 = $this->input->post("passNew2");
        $motivo = "Usuario";

        $val = $this->ChangePass($codigo, $usuario, $passAct, $passNew1, $passNew2, $motivo);
        echo $val;
    }

    public function ResetPass($codigo, $usuario) {
        $idPermiso = 4;
        $page = validarPermisoPagina($idPermiso);

        $passAct = "";
        $passNew1 = "Ediciones123";
        $passNew2 = "Ediciones123";
        $motivo = "Reset";

        $val = $this->ChangePass($codigo, $usuario, $passAct, $passNew1, $passNew2, $motivo);
        if ($val == 1) {
            $this->session->set_flashdata("msg", "Se Reseteó la Contraseña del Usuario <b>" . $usuario . "</b>");
            redirect(base_url("/Mantenimiento/Usuarios/Consultar/") . $codigo . "/");
        } else {
            $this->session->set_flashdata("error", "No se pudo Resetear la Contraseña del Usuario <b>" . $usuario . "</b>.<br>" . $val);
            redirect(base_url("/Mantenimiento/Usuarios/Admin/"));
        }
    }

    public function ChangePass($codigo, $usuario, $passAct, $passNew1, $passNew2, $motivo) {
        $users = $this->Usuarios_model->obtenerUsuario($codigo);
        if (isset($users) && $users == FALSE) {
            return "El Usuario <b>" . $users[0]['Usuario'] . "</b> no existe en la base de datos.";
        } else {
            $validar = $this->validarPass($motivo, $passAct, $users);
            if ($validar == FALSE) {
                return "La Contraseña Actual es incorrecta.";
            } else {
                if ($passNew1 != $passNew2) {
                    return "Las Contraseñas No Coinciden";
                } else {
                    $p = $this->encriptarPassSalt($passNew2, $users[0]["Salt"]);

                    //Datos Auditoría
                    $user = $this->session->userdata('Usuario');
                    $fecha = date("Y-m-d H:i:s");

                    if ($motivo == "Reset") {
                        $cambioPass = 1;
                    } else {
                        $cambioPass = 0;
                    }

                    $dataUsuario = array(
                        "Pass" => $p["pass"],
                        "CambioPass" => $cambioPass,
                        "UsuarioModificacion" => $user,
                        "FechaModificacion" => $fecha
                    );

                    try {
                        if ($this->Usuarios_model->update($codigo, $dataUsuario)) {
                            $modulo = "Cambio Contraseña";
                            $tabla = "Usuarios";
                            $accion = "Cambio Contraseña";
                            $data = compararCambiosLog($users, $dataUsuario);
                            //var_dump($data);
                            if (count($data) > 2) {
                                $data['Codigo'] = $codigo;
                                if ($motivo == "Usuario") {
                                    $data['Observaciones'] = "Cambio de Contraseña\n---\nSe genera Cambio de Contraseña a Petición del usuario: " . $user . "\n \nObservación automática.";
                                } else if ($motivo == "Reset") {
                                    $accion = "Reset Contraseña";
                                    $data['Observaciones'] = "Cambio de Contraseña\n---\nSe genera Cambio de Contraseña.\nContraseña Reseteada por: " . $user . "\n \nObservación automática.";
                                } else {
                                    $data['Observaciones'] = "Cambio de Contraseña\n---\nSe genera Cambio de Contraseña a Petición del usuario: " . $user . "\n \nObservación automática.";
                                }
                                $llave = $codigo;
                                $sql = LogSave($data, $modulo, $tabla, $accion, $llave);
                                return 1;
                            }
                        } else {
                            return "No se pudo Cambiar la Contraseña. Actualice la página y vuelva a intentarlo.";
                        }
                    } catch (Exception $e) {
                        return 'Ha habido una excepción: ' . $e->getMessage() . "<br>";
                    }
                }
            }
        }
    }

    public function validarPass($motivo, $passAct, $users) {
        $validar = false;
        if ($motivo == "Reset") {
            $validar = TRUE;
        } else {
            $p = $this->encriptarPassSalt($passAct, $users[0]["Salt"]);
            if ($p["pass"] == $users[0]["Pass"]) {
                $validar = TRUE;
            }
        }

        return $validar;
    }

}
