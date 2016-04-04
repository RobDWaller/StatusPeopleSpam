USE statuspeople_spam;

TRUNCATE spsp_fakers_wall;

INSERT INTO spsp_fakers_wall (twitterid, screen_name, avatar, spam, potential, checks, followers, updated)
SELECT ss.twitterid, ui.screen_name, ui.avatar, ss.spam, ss.potential, ss.checks, ss.followers, ss.updated
FROM spsp_spam_scores AS ss
JOIN spsp_user_info AS ui ON ss.twitterid = ui.twitterid
WHERE (ss.spam/ss.checks) * 100 > 90
ORDER BY updated DESC
LIMIT 0, 300;