=begin
Ruby script convert a santander csv statement into a format
to be imported into crunch.co.uk accounting system

First ever Ruby Script! Woo!

Usage:
ruby convert-statement.rb statements/2011-10.csv > converted/converted.csv
=end

#arguments
in_file = ARGV[0]
total = ARGV[1].to_f

# read the file in
aFile = File.new(in_file)
lines = aFile.readlines
lines.pop # remove last line

statement = []
lines.each {| line |

  line.chomp!
  cells = line.split(',')
  cells.shift # remove the first cell

  statement.push(cells)
}

# sort the statement
statement.reverse!

# header
puts ['Date','Reference','Amount','Balance',].join(',')

statement.each_with_index {| cells, i |
  # Set the running total
  if total
    if i > 0
      total += cells[2].to_f
    end
    cells.push(total.round(2));
  end

  puts cells.join(',')
}