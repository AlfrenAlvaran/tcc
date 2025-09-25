<?php

require_once __DIR__ . '/../config/database.php';


class Teacher extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllTeachers(): array
    {
        $sql = "SELECT id, fname, lname 
                FROM accounts 
                WHERE acc_level = 7";
        return $this->view($sql) ?: [];
    }

    public function getSchedule(int $teacher_id): array
    {
        $teacher_id = intval($teacher_id);

        $sql = "
            SELECT 
                s.id, 
                s.day, 
                s.time_start, 
                s.time_end, 
                s.room, 
                sub.sub_name AS subject_name, 
                sub.sub_code AS code
            FROM schedule s
            JOIN subjects sub ON s.subject_id = sub.sub_id
            WHERE s.teacher_id = {$teacher_id}
            ORDER BY FIELD(s.day,
                'Monday','Tuesday','Wednesday',
                'Thursday','Friday','Saturday'
            ), s.time_start
        ";

        return $this->view($sql) ?: [];
    }

    public function getSchedules($teacher_id)
    {
        $sql = "SELECT s.*, sub.sub_name, sub.sub_code 
            FROM schedules s
            JOIN subjects sub ON s.subject_id = sub.sub_id
            WHERE s.teacher_id = '$teacher_id'
            ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), time_start";
        $results = $this->view($sql);
        return $results ?: [];
    }


    public function getAvailableSubjects(int $teacher_id): array
    {
        $teacher_id = intval($teacher_id);

        $sql = "
            SELECT sub_id, sub_name, sub_code 
            FROM subjects 
            WHERE sub_id NOT IN (
                SELECT subject_id 
                FROM schedule 
                WHERE teacher_id = {$teacher_id}
            )
            ORDER BY sub_name
        ";

        return $this->view($sql) ?: [];
    }

    public function getAvailableTimeSlots(int $teacher_id, string $day, ?string $room = null): array
    {
        $teacher_id = intval($teacher_id);
        $day = $this->escape($day);
        $roomCondition = $room ? " AND room = '" . $this->escape($room) . "'" : "";

        $sql = "
            SELECT time_start, time_end, room
            FROM schedule
            WHERE teacher_id = {$teacher_id}
              AND day = '{$day}'
              {$roomCondition}
        ";

        $occupied = $this->view($sql) ?: [];

        $all_slots = [
            ['07:00:00', '08:00:00'],
            ['08:00:00', '09:00:00'],
            ['09:00:00', '10:00:00'],
            ['10:00:00', '11:00:00'],
            ['11:00:00', '12:00:00'],
            ['12:00:00', '13:00:00'],
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
            ['15:00:00', '16:00:00'],
            ['16:00:00', '17:00:00'],
            ['17:00:00', '18:00:00'],
            ['18:00:00', '19:00:00'],
        ];

        $available = [];

        foreach ($all_slots as $slot) {
            $conflict = false;
            foreach ($occupied as $o) {
                if ($slot[0] < $o['time_end'] && $slot[1] > $o['time_start']) {
                    $conflict = true;
                    break;
                }
            }
            if (!$conflict) {
                $available[] = $slot;
            }
        }

        return $available;
    }

    public function isRoomOccupied(string $day, string $start_time, string $end_time, string $room): bool
    {
        $day = $this->escape($day);
        $start_time = $this->escape($start_time);
        $end_time = $this->escape($end_time);
        $room = $this->escape($room);

        $sql = "
        SELECT COUNT(*) as cnt 
        FROM schedule 
        WHERE day = '{$day}' 
          AND room = '{$room}'
          AND (
              (time_start <= '{$start_time}' AND time_end > '{$start_time}') OR
              (time_start < '{$end_time}' AND time_end >= '{$end_time}') OR
              (time_start >= '{$start_time}' AND time_end <= '{$end_time}')
          )
    ";

        $row = $this->getdata($sql);
        return $row && $row['cnt'] > 0;
    }

    public function addSchedule(
        int $teacher_id,
        int $subject_id,
        string $day,
        string $start_time,
        string $end_time,
        string $room
    ): bool {
        $teacher_id = intval($teacher_id);
        $subject_id = intval($subject_id);
        $day = $this->escape($day);
        $start_time = $this->escape($start_time);
        $end_time = $this->escape($end_time);
        $room = $this->escape($room);

        if ($this->isRoomOccupied($day, $start_time, $end_time, $room)) {
            return false;
        }

        $sqlCheck = "
            SELECT COUNT(*) as cnt 
            FROM schedule
            WHERE teacher_id = {$teacher_id}
              AND day = '{$day}'
              AND (
                  (time_start <= '{$start_time}' AND time_end > '{$start_time}') OR
                  (time_start < '{$end_time}' AND time_end >= '{$end_time}') OR
                  (time_start >= '{$start_time}' AND time_end <= '{$end_time}')
              )
        ";
        $row = $this->getdata($sqlCheck);
        if ($row && $row['cnt'] > 0) {
            return false;
        }

        $sqlInsert = "
            INSERT INTO schedule (teacher_id, subject_id, day, time_start, time_end, room)
            VALUES ({$teacher_id}, {$subject_id}, '{$day}', '{$start_time}', '{$end_time}', '{$room}')
        ";

        return $this->save($sqlInsert);
    }
}
