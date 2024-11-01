'use strict';

/**
 * Service Worker of SuperPWA
 * To learn more and add one to your website, visit - https://superpwa.com
 */
 
const cacheName = 'http://piteronline.tv-superpwa-2.2.31';
const startPage = 'https://piteronline.tv/?post_type=acf-taxonomy&#038;p=29927';
const offlinePage = 'https://piteronline.tv/?post_type=acf-taxonomy&#038;p=29927';

const filesToCache = [startPage, offlinePage,'https://piteronline.tv/top-10-luchshih-shkol-parikmaherskogo-iskusstva-v-peterburge/',
'https://piteronline.tv/50-mest-v-tsentre-peterburga-kotorye-nepremenno-stoit-posetit/',
'https://piteronline.tv/15-originalnykh-mest-gde-khorosho-otmetit-detskij-den-rozhdeniya/',
'https://piteronline.tv/15-mest-gde-v-peterburge-nauchitsya-gotovit-kulinarnye-kursy-dlya-nachinayuschih/',
'https://piteronline.tv/top-10-vpechatlyayushchikh-razvlechenij-peterburga/',
'https://piteronline.tv/12-luchshih-chastnyh-shkol-sankt-peterburga/',
'https://piteronline.tv/114-interesnykh-mest-v-peterburge-kuda-skhodit-s-rebenkom/',
'https://piteronline.tv/samye-neobychnye-muzei-peterburga/',
'https://piteronline.tv/15-luchshih-detskih-teatralnyh-studiy-gde-obuchayut-akterskomu-masterstvu-v-sankt-peterburge/',
'https://piteronline.tv/top-10-kvestov-peterburga/',
'https://piteronline.tv/63-mesta-dlya-romanticheskikh-svidanij-v-peterburge/',
'https://piteronline.tv/10-luchshih-detskih-razvlekatelnyh-centrov-v-peterburge/',
'https://piteronline.tv/30-mest-kuda-poyti-s-druzyami-v-peterburge-v-lyuboe-vremya-goda/',
'https://piteronline.tv/samye-ekstremalnye-attraktsiony-peterburga/',
'https://piteronline.tv/ploshchadki-peterburga-gde-mozhno-podelat-tryuki-na-skejtborde/',
'https://piteronline.tv/skalodromy-v-sankt-peterburge/',
'https://piteronline.tv/7-mest-v-tsentre-peterburga-s-zavtrakami-do-150-rublej/',
'https://piteronline.tv/15-razvlekatelnykh-mest-gde-budet-veselo-i-detyam-i-roditelyam/',
'https://piteronline.tv/15-muzykalnyh-shkol-v-peterburge-dlya-vzroslyh/',
'https://piteronline.tv/15-priklyuchencheskih-kvestov-dlya-detey-v-sankt-peterburge/',
'https://piteronline.tv/payment-confirmation/',
'https://piteronline.tv/payment-failed/',
'https://piteronline.tv/support/',
'https://piteronline.tv/support-portal/',
'https://piteronline.tv/lost-password/',
'https://piteronline.tv/my-saves/',
'https://piteronline.tv/history/',
'https://piteronline.tv/contact/',
'https://piteronline.tv/cart/',
'https://piteronline.tv/shop/',
'https://piteronline.tv/my-interests/',
'https://piteronline.tv/checkout/',
'https://piteronline.tv/',
'https://piteronline.tv/privacy-policy/',
'https://piteronline.tv/agreements/',
'https://piteronline.tv/publichnaya-oferta/',
'https://piteronline.tv/redaktsiya/',
'https://piteronline.tv/adverts/',
'https://piteronline.tv/rubriki/',
'https://piteronline.tv/my-feed/',
];
const neverCacheUrls = [/\/wp-admin/,/\/wp-login/,/preview=true/];

// Install
self.addEventListener('install', function(e) {
	console.log('SuperPWA service worker installation');
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			console.log('SuperPWA service worker caching dependencies');
			filesToCache.map(function(url) {
				return cache.add(url).catch(function (reason) {
					return console.log('SuperPWA: ' + String(reason) + ' ' + url);
				});
			});
		})
	);
});

// Activate
self.addEventListener('activate', function(e) {
	console.log('SuperPWA service worker activation');
	e.waitUntil(
		caches.keys().then(function(keyList) {
			return Promise.all(keyList.map(function(key) {
				if ( key !== cacheName ) {
					console.log('SuperPWA old cache removed', key);
					return caches.delete(key);
				}
			}));
		})
	);
	return self.clients.claim();
});

// Range Data Code
var fetchRangeData = function(event){
    var pos = Number(/^bytes\=(\d+)\-$/g.exec(event.request.headers.get('range'))[1]);
            console.log('Range request for', event.request.url, ', starting position:', pos);
            event.respondWith(
              caches.open(cacheName)
              .then(function(cache) {
                return cache.match(event.request.url);
              }).then(function(res) {
                if (!res) {
                  return fetch(event.request)
                  .then(res => {
                    return res.arrayBuffer();
                  });
                }
                return res.arrayBuffer();
              }).then(function(ab) {
                return new Response(
                  ab.slice(pos),
                  {
                    status: 206,
                    statusText: 'Partial Content',
                    headers: [
                      // ['Content-Type', 'video/webm'],
                      ['Content-Range', 'bytes ' + pos + '-' +
                        (ab.byteLength - 1) + '/' + ab.byteLength]]
                  });
              }));
}

// Fetch
self.addEventListener('fetch', function(e) {
	
	// Return if the current request url is in the never cache list
	if ( ! neverCacheUrls.every(checkNeverCacheList, e.request.url) ) {
	  console.log( 'SuperPWA: Current request is excluded from cache.' );
	  return;
	}
	
	// Return if request url protocal isn't http or https
	if ( ! e.request.url.match(/^(http|https):\/\//i) )
		return;
	
    	// Return if request url is from an external domain.
	if ( new URL(e.request.url).origin !== location.origin )
		return;
    
			// For POST requests, do not use the cache. Serve offline page if offline.
			if ( e.request.method !== 'GET' ) {
				e.respondWith(
					fetch(e.request).catch( function() {
						        return caches.match(offlinePage);
					})
				);
				return;
			}
			
			// For Range Headers
			if (e.request.headers.has('range')) {
				return;
			}
			// Revving strategy
			if ( (e.request.mode === 'navigate' || e.request.mode === 'cors') && navigator.onLine ) {
				e.respondWith(
					fetch(e.request).then(function(response) {
						return caches.open(cacheName).then(function(cache) {
							cache.put(e.request, response.clone());
							return response;
						});  
					}).catch(function(){
						// If the network is unavailable, get
						return cache.match(e.request.url);
					})
				);
				return;
			}

			//strategy_replace_start
			e.respondWith(
				caches.match(e.request).then(function(response) {
					return response || fetch(e.request).then(function(response) {
						return caches.open(cacheName).then(function(cache) {
							cache.put(e.request, response.clone());
							return response; 
						});  
					});
				}).catch(function() {
					return caches.match(offlinePage);
				})
			);
			//strategy_replace_end


});

// Check if current url is in the neverCacheUrls list
function checkNeverCacheList(url) {
	if ( this.match(url) ) {
		return false;
	}
	return true;
}
importScripts("https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js");
	            if(workbox.googleAnalytics){
                  try{
                    workbox.googleAnalytics.initialize();
                  } catch (e){ console.log(e.message); }
                }