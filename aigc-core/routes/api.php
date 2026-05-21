
use App\Ai\Agents\LinguisticTutor;
use Illuminate\Http\Request;

Route::post('/ai/analyze-code', function (Request $request) {
    // This is the gate that connects your Lab to the Agent
    return [
        'analysis' => LinguisticTutor::make()->analyze($request->lang, $request->code)
    ];
});
