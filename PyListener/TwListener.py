import time
import httplib
import json
import tweepy
import os
import keys

api = keys.getTwitterApi()
auth = keys.getTwitterAuth()


class CustomStreamListener(tweepy.StreamListener):
    
    def __init__(self, api):
        self.api = api
        super(tweepy.StreamListener, self).__init__()
    
    def on_data(self, tweet):
        
        print "recibido"
        tweet_json = json.loads(tweet)
            
            #for tweet in tweet_json:
    	    #-----------------------------------------------------------------------
    	    # check whether this is a valid tweet
    	    #-----------------------------------------------------------------------
        if "entities" in tweet_json:
            
            print "ok datos"
        #-----------------------------------------------------------------------
        # are we mentioned within this tweet?
        #-----------------------------------------------------------------------
            mentions = tweet_json["entities"]["user_mentions"]
            mentioned_users = [ mention["screen_name"] for mention in mentions ]
                
            if keys.username in mentioned_users:
                    
                print "nueva mension"
            #-----------------------------------------------------------------------
	        # update our status with a thank you message directed at the source.
	    # use try/except to catch potential failures.
        #-----------------------------------------------------------------------
                web = httplib.HTTPSConnection('paths.manuchis.com.ar')
                web.request("GET", "/tweet.php")
                r1 = web.getresponse()
                print r1.status, r1.reason
                print r1.read()
        
        #print ("Stored in MongoDB & replied to: @%s at %s " % (tweet['user']['screen_name'] , time.strftime("%a, %d %b %Y %H:%M:%S +0000", time.gmtime()) ) )
    
    def on_error(self, status):
        
        print "error"
        print status
        return True # Don't kill the stream
    
    def on_timeout(self):
        
        print "timeout"
        return True # Don't kill the stream


sapi = tweepy.streaming.Stream(auth, CustomStreamListener(api))
sapi.filter(track=[keys.username])
    