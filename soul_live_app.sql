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

select
member.member_name,band.band_name,live.live_name
    from member
        inner join formation
            on member.member_id=formation.member_id
        INNER JOIN band
            ON formation.band_id=band.band_id
        INNER JOIN live
            ON band.live_id=live.live_id
    WHERE band.live_id='201901A'
    ORDER BY band.band_id;