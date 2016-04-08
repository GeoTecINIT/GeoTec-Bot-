#!/usr/bin/python
 # coding: latin-1
import os, sys
import time
import httplib
import json
import tweepy
import keys
import urllib
import re

api = keys.getTwitterApi()
auth = keys.getTwitterAuth()
web = keys.getWebConn()

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
                print ("nueva mension a las " + time.strftime("%a, %d %b %Y %H:%M:%S +0000", time.gmtime()))
            #-----------------------------------------------------------------------
	        # update our status with a thank you message directed at the source.
	        # use try/except to catch potential failures.
            #-----------------------------------------------------------------------
                
                say = tweet_json["text"]
                user = tweet_json["user"]["screen_name"]
                convo_id = tweet_json["user"]["id"]
                message_id = tweet_json["id_str"]
                #say = urllib.urlencode(f)
                params = urllib.urlencode({'say': say, 'convo_id': convo_id, 'user': user, 'format': 'json', 'update': 'update'})
                headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain"}
                web.request("POST", "/tweet-py.php", params, headers)
                print ("se envía petición a las " + time.strftime("%a, %d %b %Y %H:%M:%S +0000", time.gmtime()))
                r1 = web.getresponse()
                print ("se recibe petición a las " + time.strftime("%a, %d %b %Y %H:%M:%S +0000", time.gmtime()))
                print r1
                r2 = json.loads(r1.read())
                web.close()
                print r2
                response = r2
                print r1.status, r1.reason
                msg = response['botsay']
#            	phrases = [
#            	    'Ups! Parece que hubo un cortocircuito en mi sistema... ¿qué decías?',
#            	    'Vale, muy bien. No se que decirte...',
#            	    'A que te refieres?',
#            		'Ups, me han llamado de otro lado. Me lo repites?',
#            		'si, si, si',
#            		'¯\_(ツ)_/¯',
#            		'Dime algo que no sepa',	
#            		'Bueno, si te parece hablamos en otro momento',
#            		'AAAahhhhhh, estoy tan agotado... ',
#            		'Muy bien, qué te parece si seguimos la conversación más tarde?',
#            		'¿Sabes que es mi hora de descanso justo ahora? Hablemos mas tarde por favor.',
#            		'¿Te gustan los helados? ¿Podrías ir a comprar uno para regalarselo a alguien?',
#            	  ]
#                if msg == "":
#                    msg = random.choice(phrases).encode('utf-8') 
                
                status_msg = "@" +user.encode('utf-8')+ " " + msg
                print status_msg
                api.update_status(status_msg, message_id)
                #replyTweet(message_id, status)
        #print ("Stored in MongoDB & replied to: @%s at %s " % (tweet['user']['screen_name'] , time.strftime("%a, %d %b %Y %H:%M:%S +0000", time.gmtime()) ) )
   

    
    def on_error(self, status):
        
        print "error"
        print status
        return True # Don't kill the stream
    
    def on_timeout(self):
        
        print "timeout"
        return True # Don't kill the stream



kuser = keys.username
kuse = kuser.encode('utf-8')

def start_stream():
    while True:
        try:
            sapi = tweepy.streaming.Stream(auth, CustomStreamListener(api))
            sapi.filter(track=[kuse])
        except (KeyboardInterrupt, SystemExit):
                raise
        except: 
            continue
start_stream()