<?php

require_once __DIR__ . '/../config/database.php';


class Teacher extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function connection()
    {
        return $this->conn;
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
            sub.sub_id,
            sub.sub_code,
            sub.sub_name,
            sub.withLab,
            sub.units,
            p.program_id,
            p.p_code,
            p.p_des,
            p.p_major,
            p.p_year
        FROM schedule s
        JOIN subjects sub ON s.subject_id = sub.sub_id
        JOIN programs p ON s.program_id = p.program_id
        WHERE s.teacher_id = {$teacher_id}
        ORDER BY FIELD(s.day,
            'Monday','Tuesday','Wednesday',
            'Thursday','Friday','Saturday'
        ), s.time_start
    ";

        return $this->view($sql) ?: [];
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
               
                $slotStart = strtotime($slot[0]);
                $slotEnd   = strtotime($slot[1]);
                $occStart  = strtotime($o['time_start']);
                $occEnd    = strtotime($o['time_end']);

               
                if ($slotStart >= $occStart && $slotEnd <= $occEnd) {
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



    public function getPrograms()
    {
        $sql = "SELECT * FROM programs ORDER BY p_code";
        return $this->view($sql) ?: [];
    }

    public function getAllSchedules(int $teacher_id): array
    {
        $teacher_id = intval($teacher_id);

        $sql = "
        SELECT 
            sc.id AS schedule_id,
            subj.sub_code,
            subj.sub_name,
            subj.units,
            p.p_code,
            p.p_year,
            sc.day,
            sc.time_start,
            sc.time_end,
            COALESCE(sc.room, 'No Room Assigned') AS room,
            CONCAT(a.fname, ' ', a.lname) AS teacher_name,
            GROUP_CONCAT(CONCAT(s.Student_FName, ' ', s.Student_LName) SEPARATOR ', ') AS students
        FROM schedule sc
        JOIN subjects subj ON sc.subject_id = subj.sub_id
        JOIN programs p ON sc.program_id = p.program_id
        JOIN accounts a ON sc.teacher_id = a.id
        LEFT JOIN enrolled_students es ON es.schedule_id = sc.id
        LEFT JOIN students s ON es.student_id = s.Student_id
        WHERE sc.teacher_id = {$teacher_id}
        GROUP BY sc.id, subj.sub_code, subj.sub_name, subj.units, 
                 p.p_code, p.p_year, sc.day, sc.time_start, sc.time_end, sc.room, teacher_name
        ORDER BY sc.day, sc.time_start, subj.sub_code
    ";

        return $this->view_assoc($sql) ?: [];
    }


    public function showTable()
    {
        return $this->view("SHOW TABLES");
    }
}
