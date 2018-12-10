<?php

namespace MyFW\App\Controllers;

use MyFW\app\Models\Message;
use MyFW\app\Models\User;
use MyFW\core\Context;
use MyFW\core\Controller;

class ControllerMessages extends Controller
{

    public function defaut(){
        if($this->_env->getData('isConnected')) {
            //echo $this->_env->session->getUserId();
            $messages = Message::select('id', 'title')->where('status', 0)->where('user_id_reader', $this->_env->session->getUserId())->get()->toArray();
            header('Content-Type: application/json');
            echo json_encode(array('messages' => $messages));
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function read($message_id){
        if($this->_env->getData('isConnected')){
            $title=Message::findOrFail($message_id)->title;
            $content=Message::findOrFail($message_id)->content;
            $writer=Message::findOrFail($message_id)->writer->email;
            $msg=Message::findOrFail($message_id);
            $msg->status=true;
            $msg->save();
            $this->_env->setData('message_title',$title);
            $this->_env->setData('message_content',$content);
            $this->_env->setData('message_writer',$writer);
            echo $this->_env->twig->render('Messages.read.twig',$this->_env->displayData());
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function write(){
        if($this->_env->getData('isConnected')){
            echo $this->_env->twig->render('Messages.write.twig',$this->_env->displayData());
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function sent(){
        if($this->_env->getData('isConnected')) {
            //Vérifier si le mail est valide
            $mail_reader = $this->_env->request->getArguments()['data']['msg_reader'];
            $user_id_writer = User::where('email', $mail_reader)->first();
            if (count($user_id_writer) > 0) {
                //Ajouter le message à la BDD
                $message = Message::create(
                    ['title' => $this->_env->request->getArguments()['data']['msg_title'],
                        'content' => $this->_env->request->getArguments()['data']['msg_content'],
                        'user_id_writer' => $this->_env->session->getUserId(),
                        'user_id_reader' => $user_id_writer->id]
                );
                $message->save();
                $this->redirect('/messages/write','success',"Message envoyé");
            }else{
                $this->redirect('/messages/write','danger',"Destinataire inconnu");
            }
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }
}