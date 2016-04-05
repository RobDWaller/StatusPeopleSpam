TRUNCATE statuspeople_spam.spsp_fakers_wall;

INSERT INTO statuspeople_spam.spsp_fakers_wall (twitterid, screen_name, avatar, spam, potential, checks, followers, updated)
SELECT ss.twitterid, ss.screen_name, ss.avatar, ss.spam, ss.potential, ss.checks, ss.followers, ss.score_date
FROM statuspeople_spam_api.stpsa_spam_scores AS ss
WHERE (ss.spam/ss.checks) * 100 > 80
AND ss.followers >= 1000
AND ss.live = 1
ORDER BY ss.score_date DESC
LIMIT 0, 300;