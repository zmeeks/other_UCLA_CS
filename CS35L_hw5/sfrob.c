#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int frobcmp(const void* a, const void* b)
{                                                                            
  const char* u1;
  const char* u2;

  char v1;
  char v2;
  int i = 0;

  u1 = *(const char**)a;
  u2 = *(const char**)b;

  v1 = u1[i];
  v2 = u2[i];

  while (v1 != ' ' && v2 != ' ' ) {                                                                                                                        
    if ( v1 != v2)
    {                                                                                                                                                                   
      if((char)((int)v1^42) > (char)((int)v2^42)){ return 1;}
       else { return -1; }
    }
    i++;
    v1 = u1[i];
    v2 = u2[i];
  }


  if(v1 == v2){return 0;}
  if(v1 == ' ') {return 1;}
  if(v2 == ' ') {return -1;}

  /////Note: The below only triggers if there exists some unaccounted for error/////                                                                                                                      

    fprintf(stderr, "Error in frobcmp function");
  exit(1);


}


void mem_alloc_error_1(char* words)
{                                                                                                                                                                                
  free(words);
  fprintf(stderr, "Memory Allocation Error!");
  exit(1);
}

void mem_alloc_error_2(char** words)
{                                                                                                                                                                              
  free(words);
  fprintf(stderr, "Memory Allocation Error!");
  exit(1);
}


void stream_error_in()
{                                                                                                                                                                             
  if(ferror(stdin))
    {
      fprintf(stderr, "stream in ferror encountered");
      exit(1);
    }
}

void stream_error_out()
{                                                                                                                                                                            
  if(ferror(stdout))
    {
      fprintf(stderr, "stream out ferror encountered");
      exit(1);
    }
}

//http://stackoverflow.com/questions/105477/malloc-inside-a-function-call-ap\                                                                                                                             
pears-to-be-getting-freed-on-return
char* extract_word(int* end_flag)
{                                                                                                                                                                            
  int sizer = 8;
  int i = 0;
  int truth = 1;
  char buf;
  char* word = (char *)malloc(sizer*sizeof(char));
  if(word == NULL){ mem_alloc_error_1(word); }

  buf = getchar();
  stream_error_in();

  while(buf == ' ' || buf == EOF)
    {
      if(buf == EOF)
        {
          *end_flag = 1;
          word[0] = ' ';
          return word;
        }
      if(buf == ' ')
        {
          buf = getchar();
          stream_error_in();
        }
    }

  while(truth == 1)
    {

      if(buf == EOF)
        {
          *end_flag = 2;
          buf = ' ';
        }

      if(i < sizer)
        {
          word[i] = buf;
        }
      else
        {
          sizer = sizer*2;
          word = (char*)realloc(word, sizer*sizeof(char));
          if(word == NULL){ mem_alloc_error_1(word); }
          word[i+1] = buf;
        }

      if(buf == ' ')
        {
          if(i < sizer + 1)
            {
              word[i+1] = '\0';
              return word;
            }
          else
            {
              word = (char*)realloc(word, (sizer+1)*sizeof(char));
              if(word == NULL){ mem_alloc_error_1(word); }
              word[i+1] = '\0';
              return word;
            }
        }
      buf = getchar();
      stream_error_in();
      i++;
    }

}


char** insert_word_1(char* word, char** words, int where)
{                                                                                                                                                                           
  words[where] = word;
  return words;
}


//note: sizer was resized in main                                                                                                                                                                         
char** insert_word_2(char* word, char** words, int where, int sizer)
{                                                                            
  int i=0;                                                                                                                   

  char** word_list = (char**)malloc(sizer*sizeof(char*));

  if(word_list == NULL){ mem_alloc_error_2(words); }

  for(i;i<where;i++)
    {
      word_list[i] = words[i];
    }
                                                                                                                                                    


  word_list[where] = word;                                                                                                                                    

  return word_list;
}


void print_me(char** words, int size_of_words)
{                                                                                                                                                                             
  int i=0;
  int j;

  for(i; i<size_of_words; i++)
    {
      j=0;

      while(words[i][j] != ' ')
        {
          putchar(words[i][j]);
          stream_error_out();
          j++;
        }
      if(words[i][j] == ' ' )
        {
          putchar(words[i][j]);
          stream_error_out();
        }
    }
}


void nuclear_loop(char** words, int size_of_words)
{
  int i=0;

  for(i; i<size_of_words; i++){ free(words[i]); }

  free(words);
}



int main()
{                                                                                                                                                                          
  int* end_flag = (int*)malloc(sizeof(int));
  char* word;

  char** words;
  int my_size;

  int sizer;                                                                                                                                                              

  sizer=1;

  my_size=0;

  *end_flag = 0;

  word = extract_word(end_flag);

  if(*end_flag == 1){ exit(0);}

  while(*end_flag == 0 || *end_flag == 2)
    {

      if(my_size + 1 < sizer)
        {
          words = insert_word_1(word, words, my_size);
        }
      else
        {
          sizer=sizer*2;
          words = insert_word_2(word, words, my_size, sizer);
        }

      if(*end_flag == 2){ *end_flag = 1; }

      if(*end_flag == 0)
        {
          word = extract_word(end_flag);
        }
      my_size++;
    }

  qsort(words, my_size, sizeof(char*), frobcmp);

  print_me(words, my_size);

  nuclear_loop(words, my_size);

  exit(0);
}


