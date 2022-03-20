<?php
namespace App\Listeners;
use Dingo\Api\Event\ResponseWasMorphed;

class AddPaginationLinksToResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        if($event->response->status()==200){
            if(!isset($event->content['data'])){
                $event->content=[
                    'data'=>$event->content
                ];
            }
            $event->content['message']=$event->response->statusText();
            $event->content['status_code']=$event->response->status();
        }

    }
}
