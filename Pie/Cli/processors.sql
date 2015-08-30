use statuspeople_spam;

TRUNCATE spsp_queue_processors;

INSERT INTO spsp_queue_processors (twitterid, created)
SELECT twitterid, created 
FROM spsp_users 
WHERE live = 1 
ORDER BY created DESC 
LIMIT 0,100;
