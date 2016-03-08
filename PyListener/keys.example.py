#!/usr/bin/python

import tweepy
import httplib

consumer_key = 'Your_consumer_Key'
consumer_secret = 'Your_consumer_secret'
access_token = 'Your_access_token'
access_token_secret = 'Your_token_secret'
web = httplib.HTTPSConnection('www.path.to.your.website.com')

username = "YourUserName"

auth = tweepy.OAuthHandler( consumer_key, consumer_secret)
auth.set_access_token(access_token, access_token_secret)
api = tweepy.API(auth)

def getTwitterApi():
    return api

def getTwitterAuth():
    return auth

if __name__ == "__main__":
    print ("You get your Twitter API")