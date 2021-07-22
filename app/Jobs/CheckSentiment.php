<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSentiment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = [
            "body"=>$payload
        ];
        $sentimentAnalysis = $client->put("https://m0yvj161p3.execute-api.us-east-1.amazonaws.com/oats-staging/SentimentAnalysisOats", [
            RequestOptions::JSON => $payload
        ]);
        $sentimentAnalysis = json_decode($sentimentAnalysis->getBody()->getContents());
        if ($sentimentAnalysis->statusCode == 200) {
            $sentiment = $sentimentAnalysis->body->Sentiment;
            $message->sentiment = $sentiment;
            if ($sentiment == 'NEGATIVE') {
                $user->caroupoint--;
                if ($user->caroupoint < 95) {
                    Listing::where(['user_id'=>$user->id,'deprioritized'=>0])->update(['deprioritized'=>1]);
                }
                if ($user->caroupoint < 80) {
                    $user->suspension_period = Carbon::now()->addHours(6);
                }
                $user->save();
            }
        } else {
            return $this->respondError('System Error',500);
        }
    }
}
