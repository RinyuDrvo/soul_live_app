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

-- select
--     band.performance_num,band.band_id,band.band_name,member.member_name,band.performance_time
--     from member
--         inner join formation
--             on member.member_id=formation.member_id
--         INNER JOIN band
--             ON formation.band_id=band.band_id
--     WHERE band.live_id='201901A'
--     ORDER BY band.band_id;

SELECT
    performance_num,band_id,band_name,performance_time
    from band
        WHERE live_id='201901A'
        ORDER BY performance_num;