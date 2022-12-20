<?php

namespace App\Http\Controllers\Dashboard\Organiser;

use App\Http\Controllers\Controller;
use App\Models\TicketModel;
use Illuminate\Http\Request;
use Illuminate\support\facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        return "check is ok";
    }

    public function getAllTickets(Request $req)
    {
        $tickets = DB::table('eventic_ticket_reservation as main')
                        ->select(
                            'main.quantity as tickets_amount',
                            DB::raw('concat(user.firstname," ",user.lastname) as user_name'),
                            'user.avatar_name as user_profile',
                            'translation.name as event_name',
                        )
                        ->join('eventic_event_date_ticket as date_ticket','main.eventticket_id','=','date_ticket.id')
                        ->join('eventic_event_date as event_date','date_ticket.eventdate_id','=','event_date.id')
                        ->join('eventic_event as event','event_date.event_id','=','event.id')
                        ->join('eventic_venue as venue','event_date.venue_id','=','venue.id')
                        ->join('eventic_user as user','main.user_id','=','user.id')
                        ->join('eventic_event_translation as translation','event_date.event_id','=','translation.translatable_id')
                        ->where('event.organizer_id',$req->post('oid'))
                        ->get();
        return $tickets ? response(['tickets'=>$tickets,'msg'=>'Tickets Found','status'=>200],200) : response(['msg'=>'Tickets Not Found','status'=>201],201);
    }

    public function ticketDetails(Request $req){
        $tickets = DB::table('eventic_ticket_reservation as main')
                    ->select(
                        'main.quantity',
                        DB::raw('concat(user.firstname," ",user.lastname) as user_name'),
                        'user.avatar_name as user_profile',
                        'translation.name as event_name',
                        )
                    ->join('eventic_user as user','main.user_id','=','user.id')
                    ->join('eventic_event_date_ticket as event_date_ticket','main.eventticket_id','=','event_date_ticket.id')
                    ->join('eventic_event_date as event_date','event_date_ticket.eventdate_id','=','event_date.id')
                    ->join('eventic_event_translation as translation','event_date.event_id','=','translation.translatable_id')
                    ->where(['main.id'=>$req->post('tid')])
                    ->get();
        return $tickets ? response(['tickets'=>$tickets,'msg'=>'Tickets Found','status'=>200],200) : response(['msg'=>'Tickets Not Found','status'=>201],201);
    }

    public function searchTicket(Request $req){
        $tickets = DB::table('eventic_ticket_reservation as main')
                        ->select(
                            'reservation.id as ticket_id',
                            'main.quantity as tickets_amount',
                            DB::raw('concat(user.firstname," ",user.lastname) as user_name'),
                            'user.avatar_name as user_profile',
                            'translation.name as event_name',
                            'date_ticket.reference as reference',
                        )
                        ->join('eventic_event_date_ticket as date_ticket','main.eventticket_id','=','date_ticket.id')
                        ->join('eventic_ticket_reservation as reservation','date_ticket.id','=','reservation.eventticket_id')
                        ->join('eventic_event_date as event_date','date_ticket.eventdate_id','=','event_date.id')
                        ->join('eventic_event as event','event_date.event_id','=','event.id')
                        ->join('eventic_venue as venue','event_date.venue_id','=','venue.id')
                        ->join('eventic_user as user','main.user_id','=','user.id')
                        ->join('eventic_event_translation as translation','event_date.event_id','=','translation.translatable_id')
                        ->where('translation.name','like','%'.$req->text.'%')
                        ->orWhere('user.firstname','like','%'.$req->text.'%')
                        ->orWhere('user.lastname','like','%'.$req->text.'%')
                        ->orWhere('event_date.startdate','like','%'.$req->text.'%')
                        ->orWhere('event_date.enddate','like','%'.$req->text.'%')
                        ->orWhere('date_ticket.reference','like','%'.$req->text.'%')
                        ->get();
                        // ->toSql();
        return $tickets ? response(['Count'=>count($tickets),'msg'=>'Tickets Found','status'=>200,'tickets'=>$tickets],200): response(['msg'=>'Tickets Not Found','status'=>201],201);
    }
}
