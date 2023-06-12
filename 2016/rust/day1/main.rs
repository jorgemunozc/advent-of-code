use std::collections::HashMap;
#[derive(Debug)]
struct Position {
    x: i32,
    y: i32,
    direction_in_deg: i32,
}

#[derive(Debug)]
enum Direction {
    Left,
    Right,
}

#[derive(Debug)]
struct Instruction {
    direction: Direction,
    distance_to_walk: i32,
}

fn parse_instruction(raw_instruction: &str) -> Instruction {
    let (direction, length) = raw_instruction.split_at(1);
    let direction = match direction {
        "L" => Direction::Left,
        "R" => Direction::Right,
        _ => panic!("Invalid Instruction"),
    };
    Instruction {
        direction,
        distance_to_walk: length.parse::<i32>().unwrap_or(0),
    }
}

fn move_pos(current_pos: &mut Position, instruction: Instruction) -> Vec<(i32, i32)> {
    let cos = [1, 0, -1, 0];
    let sin = [0, 1, 0, -1];
    let mut visited_points: Vec<(i32, i32)> = Vec::new();

    current_pos.direction_in_deg = match instruction.direction {
        Direction::Left => ((current_pos.direction_in_deg + 90) % 360 + 360) % 360,
        Direction::Right => ((current_pos.direction_in_deg - 90) % 360 + 360) % 360,
    };
    let pos: i32 = current_pos.direction_in_deg / 90;

    for i in 1..=instruction.distance_to_walk {
        let x = current_pos.x + (cos[pos as usize] * i);
        let y = current_pos.y + (sin[pos as usize] * i);
        visited_points.push((x, y))
    }
    current_pos.x += cos[pos as usize] * instruction.distance_to_walk;
    current_pos.y += sin[pos as usize] * instruction.distance_to_walk;
    visited_points
}

fn distance_from_shortest_route<'a, T>(
    instructions: T,
    stop_when_point_already_visited: bool,
) -> u16
where
    T: IntoIterator<Item = &'a str>,
{
    let mut current_pos = Position {
        x: 0,
        y: 0,
        direction_in_deg: 90,
    };
    let mut visited_points_in_route: HashMap<(i32, i32), i32> = HashMap::new();

    for raw_instruction in instructions {
        let instruction = parse_instruction(raw_instruction);
        // println!("From ({}, {})", current_pos.x, current_pos.y);
        let visited_points_in_current_move = move_pos(&mut current_pos, instruction);

        for point in visited_points_in_current_move {
            match visited_points_in_route.get(&point) {
                Some(_) => {
                    if stop_when_point_already_visited {
                        return (point.0.abs() + point.1.abs()) as u16;
                    }
                }
                None => {
                    visited_points_in_route.insert(point, 1);
                }
            };
        }
        // println!("And moved to: ({},{})", current_pos.x, current_pos.y);
    }
    (current_pos.x.abs() + current_pos.y.abs()) as u16
}

#[allow(dead_code)]
fn part1() {
    let directions = include_str!("../inputs/day1.dat").split(',').map(str::trim);
    println!(
        "total distance {:?}",
        distance_from_shortest_route(directions, false)
    )
}

fn part2() {
    let directions = include_str!("../inputs/day1.dat").split(',').map(str::trim);
    // let directions = ["R8", "R4", "R4", "R8"];

    println!(
        "Total distance: {}",
        distance_from_shortest_route(directions, true)
    )
}
fn main() {
    part2();
}

#[cfg(test)]
mod tests {
    use crate::distance_from_shortest_route;

    #[test]
    fn test_goes_to_right_place() {
        let directions = ["R5", "L5", "R5", "R3"];
        assert_eq!(distance_from_shortest_route(directions, false), 12)
    }
    #[test]
    fn test_goes_to_negative() {
        let directions = ["R1", "R1", "R1", "L1", "L1", "R1", "R1", "L1", "R1"];
        assert_eq!(distance_from_shortest_route(directions, false), 5);
    }

    #[test]
    fn test_distance_first_repeated() {
        let directions = ["R8", "R4", "R4", "R8"];
        assert_eq!(distance_from_shortest_route(directions, true), 4)
    }
}
