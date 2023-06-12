use core::panic;
use std::f32::consts::PI;
struct Position {
    x: f32,
    y: f32,
    direction_in_radians: i32,
}

struct Pos {
    x: f32,
    y: f32,
    facting_to: Facing,
}
#[derive(Debug)]
enum Direction {
    Left,
    Right,
}
enum Facing {
    Nort,
    South,
    West,
    East,
}
#[derive(Debug)]
struct Instruction {
    direction: Direction,
    spaces: f32,
}

fn parse_instruction(raw_instruction: &str) -> Instruction {
    let (direction, length) = raw_instruction.split_at(1);
    let direction = match direction {
        "L" => Direction::Left,
        "R" => Direction::Right,
        _ => panic!("oops"),
    };
    Instruction {
        direction,
        spaces: length.parse().unwrap_or_default(),
    }
}

fn move_pos(current_pos: &mut Position, instruction: Instruction) {
    current_pos.direction_in_radians = match instruction.direction {
        Direction::Left => (current_pos.direction_in_radians + 90) % 360,
        Direction::Right => (current_pos.direction_in_radians - 90) % 360,
    };
    let angle_in_radians = current_pos.direction_in_radians as f32 * (PI / 180.0);
    current_pos.x += angle_in_radians.cos() * instruction.spaces;
    current_pos.y += angle_in_radians.cos() * instruction.spaces;
    println!("x: {} - y: {}", current_pos.x, current_pos.y);
    println!(
        "direction: {:?} -  {}",
        instruction.direction, current_pos.direction_in_radians
    );
}

fn move_normal(current_pos: &mut Pos, instruction: Instruction) {
    match current_pos.facting_to {
        
    }
}

fn distance_from_shortest_route<'a, T>(instructions: T) -> u16
where
    T: IntoIterator<Item = &'a str>,
{
    let mut initial_pos = Position {
        x: 0.0,
        y: 0.0,
        direction_in_radians: 90,
    };
    for instruction in instructions {
        let movement = parse_instruction(instruction);
        move_pos(&mut initial_pos, movement);
        // println!("We're now in ({}, {})", initial_pos.x, initial_pos.y)
    }
    (initial_pos.x.abs() + initial_pos.y.abs()) as u16
}

fn main() {
    // let directions = include_str!("../inputs/day1.dat").split(',').map(str::trim);
    let directions = ["R1", "R1", "R1", "L1", "L1", "R1", "R1", "L1", "R1"];
    println!("printin shortest route...");
    let distance_to_destination = distance_from_shortest_route(directions);
    println!("total distance {:?}", distance_to_destination)
}

#[cfg(test)]
mod tests {
    use crate::distance_from_shortest_route;

    #[test]
    fn test_goes_to_right_place() {
        let directions = ["R5", "L5", "R5", "R3"];
        assert_eq!(distance_from_shortest_route(directions), 12)
    }
    #[test]
    fn test_goes_to_negative() {
        let directions = ["R1", "R1", "R1", "L1", "L1", "R1", "R1", "L1", "R1"];
        assert_eq!(distance_from_shortest_route(directions), 5);
    }
}
