<?php
namespace App\Http\Controllers\Api\Transaction;


use App\Models\Account;
use App\MyConfig\Globals;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponse;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TransactionController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = new Collection();
        if($request->has('filter') && !empty($request->filter) && $request->has('value') && !empty($request->value)){
            $request->validate(
                [
                    'filter' => [ Rule::in(['origin','destiny'])],
                    'value' => ['required', 'integer']
                ],
                [
                    '*' => 'Parametros incorrectos en el filtro' 
                ]
            );
            
            if($request->filter ==='origin'){
                $transactions = Auth::user()->transactions()->where('origin_id', "like", "%$request->value%")->orderBy('created_at', 'desc')->get();
            }
            if($request->filter ==='destiny'){
                $transactions = Auth::user()->transactions()->where('destiny_id', "like", "%$request->value%")->orderBy('created_at', 'desc')->get();
            }
        }else{
            $transactions = Auth::user()->transactions()->orderBy('created_at', 'desc')->get();
        }
        return $this->showPagination(200, $transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regex = Globals::REGEX['numbers'];
        $regexDes = Globals::REGEX['description'];
        $request->validate([
            'origin_id' => ['required', 'integer'],
            'destiny_id' => ['required', 'integer', 'different:origin_id'],
            'value' => ['required','numeric', 'min:1', "max:10000000", "regex:$regex"],
            'description' => [$request->description ? "regex:$regexDes" : ""],
        ],[
            'value.min' => "El :attribute debe ser mayor a 0",
            'value.max' => "El :attribute es muy grande"
        ]);

        $accountOrigin = Auth::user()->accounts()->where('account',$request->origin_id)->where('state', Account::ACTIVE)->first();
        if(!$accountOrigin){
            return $this->errorResponse(403, __('api.account_origin_inactive'));
        }
        $accountDestiny = Auth::user()->accounts()->where('account', $request->destiny_id)->where('state', Account::ACTIVE)->first();
        if(!$accountDestiny){
            return $this->errorResponse(403, __('api.account_destiny_inactive'));
        }
        if((int)$request->value > (int)$accountOrigin->current_value){
            return $this->errorResponse(403, __('api.account_insufficient_balance'));
        }

        $request->request->add(['user_id' => Auth::user()->id]);

        
        // Actualizar el valor de la cuenta propia
        $account = Account::where('account',$accountDestiny->account)->where('type', Account::TYPE_PROPIA)->firstOrFail();
        
        $accountOrigin->current_value = (int)$accountOrigin->current_value - (int) $request->value;
        $accountOrigin->save();
        $account->current_value = (int)$account->current_value + (int) $request->value;
        $account->save();

        $transaction = Transaction::create($request->all());
        
        return $this->showOne(200, $transaction);

    }

}
