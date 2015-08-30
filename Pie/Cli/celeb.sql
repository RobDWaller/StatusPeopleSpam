use statuspeople_spam;

INSERT INTO spsp_queue (twitterid, screen_name, created)
SELECT s.twitterid, ui.screen_name, unix_timestamp() AS created
FROM spsp_spam_scores AS s 
JOIN spsp_user_info AS ui ON s.twitterid = ui.twitterid
WHERE s.live = 1
AND s.followers >= 1000000
AND s.updated <= (UNIX_TIMESTAMP() - ((24*3600)*31)) 
GROUP BY s.twitterid
ORDER BY s.followers DESC 
LIMIT 0, 100;
