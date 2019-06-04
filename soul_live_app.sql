use heroku_42be86436ff4cd8
-- CREATE TABLE IF NOT EXISTS live(
--     live_id CHAR(7) PRIMARY KEY,
--     live_name VARCHAR(30)
-- );

-- DESC live;

-- CREATE TABLE IF NOT EXISTS band(
--     band_id CHAR(4) PRIMARY KEY,
--     live_id CHAR(7),
--     band_name VARCHAR(30),
--     performance_time VARCHAR(20),
--     performance_num INT
-- );

-- DESC band;

-- CREATE TABLE IF NOT EXISTS formation(
--     member_id CHAR(7),
--     band_id CHAR(4),
--     PRIMARY KEY(member_id,band_id)
-- );

-- DESC formation;

-- CREATE TABLE IF NOT EXISTS member(
--     member_id CHAR(7) PRIMARY KEY,
--     member_name VARCHAR(20)
-- );

-- DESC member;

-- SELECT
--     member.member_name
--     FROM member
--         INNER JOIN formation
--             ON member.member_id=formation.member_id
--         INNER JOIN band
--             ON formation.band_id=band.band_id
--     WHERE band.live_id='201901A' AND band.band_id='B001'

-- SELECT
--     performance_num,band_id,band_name,performance_time
--     from band
--         WHERE live_id='201901A'
--         ORDER BY performance_num;

-- INSERT INTO band (live_id,band_id,band_name,performance_time,performance_num)
--     VALUES ('201901A','B001','今日の飯','11:50~12:20',1);
-- INSERT INTO band (live_id,band_id,band_name,performance_time,performance_num)
--     VALUES ('201901A','B002','秋砂','12:20~12:45',2);

-- SELECT * FROM band;

-- DELETE FROM formation
--     (SELECT formation INNER JOIN band ON formation.band_id=band.band_id
--     WHERE live_id='201901A');

-- DELETE FROM formation WHERE member_id='201904';

-- INSERT INTO formation (member_id,band_id) VALUES ('2019001','B001');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019002','B001');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019003','B001');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019004','B001');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019005','B002');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019006','B002');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019007','B002');
-- INSERT INTO formation (member_id,band_id) VALUES ('2019008','B002');

-- SELECT * FROM formation;

-- DELETE FROM formation
--     WHERE band_id IN (SELECT band_id FROM band WHERE live_id='201901A');