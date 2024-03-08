class NegativeArguments(Exception):

    def __init__(self):
        super().__init__("one or more arguments are negative.")
