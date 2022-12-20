<?php

namespace App\Http\Controllers\Dashboard\Organiser;

use App\Http\Controllers\Controller;
use App\Models\EventModel;
use Illuminate\Http\Request;
use Illuminate\support\facades\DB;

class EventController extends Controller
{
    public function OrganizerAllEvents(Request $req){
        $events = DB::table('eventic_event_date as main')
                    ->select(
                        'main.event_id as event_id',
                        'translation.name as event_name',
                        'main.startdate as event_start_date',
                        'main.active as event_active_status',
                        'main.online as event_online_status',
                        'venue.state as event_state',
                        'event.image_name as event_image'
                    )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->where(['event.organizer_id'=>$req->post('oid')])
                    ->get();
        return $events ? response(['count'=>count($events),'status'=>200, 'msg'=>'Events Found','events'=>$events],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function eventDetails(Request $req){
        $events = DB::table('eventic_event_date as main')
                    // ->select(
                    //     'main.event_id as event_id',
                    //     'translation.name as event_name',
                    //     'main.startdate as event_start_date',
                    //     'main.active as event_active_status',
                    //     'main.online as event_online_status',
                    //     'venue.state as event_state',
                    //     'event.image_name as event_image'
                    // )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->where(['event.id'=>$req->eid])
                    ->get();
        return $events ? response(['msg'=>'Events Found','img_url'=>url('/uploads/events/'),'events'=>$events,'count'=>count($events),'status'=>200],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function upComingEvents(){
        $events = DB::table('eventic_event_date as main')
                    ->select(
                        'main.event_id as event_id',
                        'translation.name as event_name',
                        'main.startdate as event_start_date',
                        'main.active as event_active_status',
                        'main.online as event_online_status',
                        'venue.state as event_state',
                        'event.image_name as event_image'
                    )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->whereDate('main.startdate', '>=', date('Y-m-d'))
                    ->get();
        return $events ? response(['msg'=>'Events Found','events'=>$events,'count'=>count($events),'status'=>200],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function pastEvents(){
        $events = DB::table('eventic_event_date as main')
                    ->select(
                        'main.event_id as event_id',
                        'translation.name as event_name',
                        'main.startdate as event_start_date',
                        'main.active as event_active_status',
                        'main.online as event_online_status',
                        'venue.state as event_state',
                        'event.image_name as event_image'
                    )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->whereDate('main.enddate', '<', date('Y-m-d'))
                    ->get();
        return $events ? response(['msg'=>'Events Found','events'=>$events,'count'=>count($events),'status'=>200],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function searchEvent(Request $req){
        $events = DB::table('eventic_event_date as main')
                    ->select(
                        'main.event_id as event_id',
                        'translation.name as event_name',
                        'main.startdate as event_start_date',
                        'main.active as event_active_status',
                        'main.online as event_online_status',
                        'venue.state as event_state',
                        'event.image_name as event_image'
                    )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->where('translation.name','like','%'.$req->text.'%')
                    ->orWhere('venue.state','like','%'.$req->text.'%')
                    ->orWhere('main.enddate','like','%'.$req->text.'%')
                    ->orWhere('main.startdate','like','%'.$req->text.'%')
                    ->get();
        return $events ? response(['status'=>200, 'msg'=>'Events Found','events'=>$events],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function findEventsByLocation(Request $req)
    {
        $events = DB::table('eventic_event_date as main')
                    ->select(
                        'main.event_id as event_id',
                        'translation.name as event_name',
                        'main.startdate as event_start_date',
                        'main.active as event_active_status',
                        'main.online as event_online_status',
                        'venue.state as event_state',
                        'event.image_name as event_image'
                    )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->where('venue.state','like','%'.$req->text.'%')
                    ->get();
        return $events ? response(['count'=>count($events),'status'=>200, 'msg'=>'Events Found','events'=>$events],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function searchEventsByCategory(Request $req)
    {
        $events = DB::table('eventic_event_date as main')
                    ->select(
                        'main.event_id as event_id',
                        'translation.name as event_name',
                        'main.startdate as event_start_date',
                        'main.active as event_active_status',
                        'main.online as event_online_status',
                        'venue.state as event_state',
                        'event.image_name as event_image',
                        'event.category_id as category_id'
                    )
                    ->join('eventic_event as event','main.event_id','=','event.id')
                    ->join('eventic_category as category','event.category_id','=','category.id')
                    ->join('eventic_venue as venue','main.venue_id','=','venue.id')
                    ->join('eventic_organizer as organizer','venue.organizer_id','=','organizer.id')
                    ->join('eventic_country as country','venue.country_id','=','country.id')
                    ->join('eventic_event_translation as translation','event.id','=','translation.translatable_id')
                    ->where('category.id','=',$req->id)
                    ->get();
        return $events ? response(['count'=>count($events),'status'=>200, 'msg'=>'Events Found','events'=>$events],200): response(['msg'=>'No Events found for this Organizer','status'=>201],200);
    }

    public function allEventCategories(Request $req)
    {
        $categories = DB::table('eventic_category')->get();
        return response(['count'=>count($categories),'status'=>200,'categories' => $categories],200);
    }

    public function getEventsByPrice(Type $var = null)
    {
        
    }
}
