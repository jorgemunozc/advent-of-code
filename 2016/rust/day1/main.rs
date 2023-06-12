use core::panic;
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
    spaces: i32,
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
    let cos = [1, 0, -1, 0];
    let sin = [0, 1, 0, -1];
    current_pos.direction_in_deg = match instruction.direction {
        Direction::Left => ((current_pos.direction_in_deg + 90) % 360 + 360) % 360,
        Direction::Right => ((current_pos.direction_in_deg - 90) % 360 + 360) % 360,
    };
    let pos = current_pos.direction_in_deg / 90;
    // println!("deg: {}", current_pos.direction_in_deg);
    current_pos.x += cos[pos as usize] * instruction.spaces;
    current_pos.y += sin[pos as usize] * instruction.spaces;
}

fn distance_from_shortest_route<'a, T>(instructions: T) -> u16
where
    T: IntoIterator<Item = &'a str>,
{
    let mut initial_pos = Position {
        x: 0,
        y: 0,
        direction_in_deg: 90,
    };
    let mut visited_locations: HashMap<(i32, i32), i32> = HashMap::new();
    visited_locations.insert((0, 0), 1);
    for instruction in instructions {
        let movement = parse_instruction(instruction);
        move_pos(&mut initial_pos, movement);
        println!("");
        if visited_locations.contains_key(&(initial_pos.x, initial_pos.y)) {
            println!("posicion que se repite por primera vez {:?}", initial_pos);
            return (initial_pos.x.abs() + initial_pos.y.abs()) as u16;
        }
        visited_locations
            .entry((initial_pos.x, initial_pos.y))
            .and_modify(|count| *count += 1)
            .or_insert(1);
    }
    (initial_pos.x.abs() + initial_pos.y.abs()) as u16
}

fn main() {
    // let directions = include_str!("../inputs/day1.dat").split(',').map(str::trim);
    // let directions = ["R1", "R1", "R1", "L1", "L1", "R1", "R1", "L1", "R1"];
    let directions = ["R8", "R4", "R4", "R8"];
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

    #[test]
    fn test_distance_first_repeated() {
        let directions = ["R8", "R4", "R4", "R8"];
        assert_eq!(distance_from_shortest_route(directions), 4)
    }
}
