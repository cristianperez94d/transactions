<?php
namespace App\Http\Controllers\Api\Account;

use Faker\Factory;
use App\Models\User;
use App\Models\Account;
use App\MyConfig\Globals;
use Illuminate\Http\Request;
use App\Traits\Api\ApiResponse;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    use ApiResponse;


    public function index(){

        $accounts = Auth::user()->accounts()->get();
        return $this->showAll(200, $accounts);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $userAuth = Auth::user();

        if($userAuth->type == User::ADMIN){
            $regex = Globals::REGEX['numbers'];
            $request->validate([
                'current_value' => ['required', 'min:1', "max:10", "regex:$regex"],
                'identification' => [ 'required', 'integer'],
            ]);

            $userReq = User::where('identification', $request->identification)->firstOrFail();

            $faker = Factory::create();
            $numAccount = null;
            $intentos = 300;
            $i = 0;
            while ($i <= $intentos) {
                $num = $faker->unique()->randomNumber(8); 
                if(Account::where('account', $num)->count() <= 0 ){
                    $i=$intentos;
                    $numAccount = $num;
                    break;
                }
                $i++;
            }
            if(!$numAccount){
                return $this->errorResponse(404, __('api.account_number_error')); 
            }

            $request->request->add([
                'account' => $numAccount,
                'type' => Account::TYPE_PROPIA,
                'state' => Account::ACTIVE,
                'user_id' => $userReq->id,
            ]);

            $account = Account::create($request->all());
            
            return $this->showOne(201, $account);
        }
        
        // registrar cuenta de terceros
        $regex = Globals::REGEX['identification'];
        $request->validate([
            'identification' => ['required', "regex:$regex"],
            'account' => [ 'required', 'integer'],
        ]);
        
        $userReq = User::where('identification', $request->identification)->firstOrFail();
        $userReq->accounts()->where('account', $request->account)->where('type', Account::TYPE_PROPIA)->firstOrFail();
        
        if($request->identification === $userAuth->id) return $this->errorResponse(404, __('api.invalid_user_account_same'));
        
        $count = $userAuth->accounts()->where('account', $request->account)->count();
        if($count > 0) return $this->errorResponse(404, __('api.invalid_user_account_same'));

        $account = Account::create([
            'account' => $request->account,
            'current_value' => 0,
            'type' => Account::TYPE_TERCEROS,
            'state' => Account::ACTIVE,
            'user_id' => $userAuth->id,
        ]);

        return $this->showOne(201, $account);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // regular
        $request->validate([
            'type' => ['reqired', ],
            'state' => ['reqired', ],
            'user_id' => ['reqired', ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    } 

    public function type(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(Globals::ACCOUNT_TYPE)]
        ]);

        $accounts = Auth::user()->accounts()
            ->where('type', $request->type)
            ->get()
        ;
        
        return $this->showAll(200, $accounts);
    }

}
