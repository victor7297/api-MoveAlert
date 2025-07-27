<?php

namespace App\Http\Controllers;

use App\Models\TokenTelefono;
use Google\Auth\OAuth2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Google\Client;

class NotificacionFirebaseController extends Controller{

    private $client; // atributo de clase
    private $url;

    public function __construct(){

        /*$this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/Firebase/CredencialesFirebase.json'));
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');

         // URL como atributo de clase
        $this->url = "https://fcm.googleapis.com/v1/projects/movealert-6eb43/messages:send";*/

         $this->client = new Client();

        // Decodifica el JSON desde base64 y lo guarda temporalmente
        $credentialsBase64 = env('FIREBASE_CREDENTIALS_BASE64');

        if (!$credentialsBase64) {
            throw new \Exception('La variable FIREBASE_CREDENTIALS_BASE64 no está definida.');
        }

        $jsonString = base64_decode($credentialsBase64);

        // Guarda en un archivo temporal (opcional)
        $tempPath = storage_path('app/firebase_temp_credentials.json');
        file_put_contents($tempPath, $jsonString);

        $this->client->setAuthConfig($tempPath);
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $this->url = "https://fcm.googleapis.com/v1/projects/movealert-6eb43/messages:send";
    }

    public function EnviarNotificacionUsuario(Request $request) {

        try{

            $tokenResponse = $this->client->fetchAccessTokenWithAssertion();

            //isset Es una función nativa de PHP que verifica si una variable está definida y no es null.
            if (isset($tokenResponse['error'])) {
                return response()->json(['error' => 'Error al obtener access token', 'detail' => $tokenResponse], 500);
            }

            $accessToken = $tokenResponse['access_token'];
            $listaTokens = TokenTelefono::where('usuarioId', $request->input("idUsuario"))->get();

            foreach ($listaTokens as $token) {

                //withToken() añade el encabezado Authorization a la petición con el token
                $response = Http::withToken($accessToken)->post($this->url, [
                    'message' => [
                        'token' => $token["token"],
                        'notification' => [
                            'title' => $request->nombreEmisor,
                            'body' => $request->mensaje,
                        ],
                        //'data' => $request->input('data', []), // opcional
                    ]
                ]);

            }

            //return response()->json($response->json(), $response->status());

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }

    }

    public function EnviarNotificacionGlobal(Request $request) {

        try{

            // Obtener access_token
            $tokenResponse = $this->client->fetchAccessTokenWithAssertion();

            //isset Es una función nativa de PHP que verifica si una variable está definida y no es null.
            if (isset($tokenResponse['error'])) {
                return response()->json(['error' => 'Error al obtener access token', 'detail' => $tokenResponse], 500);
            }

            $accessToken = $tokenResponse['access_token'];
            
            //withToken() añade el encabezado Authorization a la petición con el token
            $response = Http::withToken($accessToken)->post($this->url, [
                'message' => [
                    'topic' => 'all', // el nombre del topic
                    'notification' => [
                        'title' => $request->title,
                        'body' => $request->body,
                    ],
                    //'data' => $request->input('data', []), // opcional
                ]
            ]);

            return response()->json($response->json(), $response->status());

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }

    }
}

/*
    $client = new \GuzzleHttp\Client();

    $response = $client->post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'message' => [
                'token' => $request->token,
                'notification' => [
                    'title' => $request->title,
                    'body' => $request->body,
                ],
                'data' => $request->input('data', []),
            ]
        ]
    ]);

    // Obtener el cuerpo como array
    $responseData = json_decode($response->getBody(), true);
*/
